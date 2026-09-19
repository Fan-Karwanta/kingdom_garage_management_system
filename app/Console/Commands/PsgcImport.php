<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

/**
 * Imports the PSA PSGC barangay dataset into tbl_psgc_barangays.
 *
 * Source: https://github.com/jgngo/psgc-data (PSA-derived, ~42,000 barangays).
 * The command downloads four small JSON files (region, province, muncity,
 * barangay), joins them in memory, and inserts denormalized rows so the
 * autocomplete endpoint can answer with a single indexed LIKE query.
 *
 * Usage:
 *   php artisan psgc:import            # download + import
 *   php artisan psgc:import --fresh    # truncate first
 *   php artisan psgc:import --local=storage/psgc  # use local files instead of downloading
 */
class PsgcImport extends Command
{
    protected $signature = 'psgc:import
        {--fresh : Truncate tbl_psgc_barangays before importing}
        {--local= : Path to a local directory containing region/province/muncity/barangay .json files (skip download)}';

    protected $description = 'Import the PSA PSGC barangay dataset into tbl_psgc_barangays for address autocomplete.';

    private const BASE_URL = 'https://raw.githubusercontent.com/jgngo/psgc-data/master/json/';

    public function handle()
    {
        if (! Schema::hasTable('tbl_psgc_barangays')) {
            $this->error('Table tbl_psgc_barangays does not exist. Run migrations first: php artisan migrate');

            return 1;
        }

        if ($this->option('fresh')) {
            $this->info('Truncating tbl_psgc_barangays...');
            DB::table('tbl_psgc_barangays')->truncate();
        }

        $existing = DB::table('tbl_psgc_barangays')->count();
        if ($existing > 0 && ! $this->option('fresh')) {
            if (! $this->confirm("tbl_psgc_barangays already has {$existing} rows. Append more?")) {
                $this->info('Aborted.');

                return 0;
            }
        }

        $localDir = $this->option('local');
        $region = $this->loadJson('region.json', $localDir);
        $province = $this->loadJson('province.json', $localDir);
        $muncity = $this->loadJson('muncity.json', $localDir);
        $barangay = $this->loadJson('barangay.json', $localDir);

        $this->info(sprintf(
            'Loaded %d regions, %d provinces, %d cities/municipalities, %d barangays.',
            count($region), count($province), count($muncity), count($barangay)
        ));

        // Index parent tables by PSGC code prefix for reliable joining.
        // The province_id / region_id fields in jgngo/psgc-data are unreliable
        // (1625 of 1648 muncity entries have a wrong province_id), so we join
        // via the PSGC code hierarchy instead:
        //   region    = first 2 digits + zeros  (e.g. 110000000)
        //   province  = first 4 digits + zeros  (e.g. 112400000)  — regular
        //             = first 6 digits + zeros  (e.g. 112402000)  — HUC
        //   muncity   = first 6 digits + zeros  (e.g. 112403000)
        //   barangay  = full 9-digit code        (e.g. 112403001)
        $regionsByCode = collect($region)->keyBy('code');
        $provincesByCode = collect($province)->keyBy('code');
        $muncitiesById = collect($muncity)->keyBy('muncity_id');

        $rows = [];
        $now = now()->toDateTimeString();
        $bar = $this->output->createProgressBar(count($barangay));
        $bar->start();

        foreach ($barangay as $b) {
            $m = $muncitiesById->get($b['muncity_id']);
            if (! $m) {
                $bar->advance();
                continue;
            }

            // Resolve province: try HUC exact match (6-digit) first, then
            // regular province (4-digit).
            $muncityPrefix6 = substr($m['code'], 0, 6) . '000';
            $muncityPrefix4 = substr($m['code'], 0, 4) . '00000';
            $p = $provincesByCode->get($muncityPrefix6)
                ?? $provincesByCode->get($muncityPrefix4);

            // Resolve region from province code prefix (first 2 digits).
            $r = null;
            if ($p) {
                $regionCode = substr($p['code'], 0, 2) . '0000000';
                $r = $regionsByCode->get($regionCode);
            }

            $barangayName = trim($b['description']);
            $cityMunName = trim($m['description']);
            $provinceName = $p ? trim($p['description']) : '';
            $regionName = $r ? trim($r['description']) : '';

            // full_address: "Barangay, City/Municipality, Province, Region"
            // Skip province when it duplicates the city (HUCs like Davao City
            // where province == municipality) and skip empty parts.
            $parts = [$barangayName, $cityMunName];
            if ($provinceName !== '' && strcasecmp($provinceName, $cityMunName) !== 0) {
                $parts[] = $provinceName;
            }
            if ($regionName !== '') {
                $parts[] = $regionName;
            }
            $fullAddress = implode(', ', $parts);

            $rows[] = [
                'psgc_code' => $b['code'],
                'region_code' => $r['code'] ?? null,
                'region_name' => $regionName ?: null,
                'province_code' => $p['code'] ?? null,
                'province_name' => $provinceName ?: null,
                'city_municipality_code' => $m['code'] ?? null,
                'city_municipality_name' => $cityMunName ?: null,
                'barangay_code' => $b['code'] ?? null,
                'barangay_name' => $barangayName,
                'full_address' => $fullAddress,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Chunk insert to avoid huge single queries.
            if (count($rows) >= 1000) {
                DB::table('tbl_psgc_barangays')->insert($rows);
                $rows = [];
            }

            $bar->advance();
        }

        if (! empty($rows)) {
            DB::table('tbl_psgc_barangays')->insert($rows);
        }

        $bar->finish();
        $this->newLine();

        $total = DB::table('tbl_psgc_barangays')->count();
        $this->info("Done. tbl_psgc_barangays now has {$total} rows.");

        return 0;
    }

    /**
     * Load a JSON file from the local directory or download it.
     */
    private function loadJson(string $filename, ?string $localDir): array
    {
        if ($localDir) {
            $path = rtrim($localDir, '/').'/'.$filename;
            if (! file_exists($path)) {
                throw new \RuntimeException("Local file not found: {$path}");
            }
            $this->line("Reading local: {$path}");

            return json_decode(file_get_contents($path), true);
        }

        $url = self::BASE_URL.$filename;
        $this->line("Downloading: {$url}");
        $response = Http::timeout(120)->get($url);
        if (! $response->ok()) {
            throw new \RuntimeException("Failed to download {$url}: HTTP ".$response->status());
        }

        return $response->json();
    }
}
