<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Local copy of the PSA PSGC barangay-level dataset.
 *
 * The 10-digit barangay PSGC code is self-contained: it encodes the region,
 * province, and city/municipality. We denormalize the parent names into this
 * table so the autocomplete endpoint can return a ready-to-show full address
 * string with a single indexed LIKE query — no joins, no external API calls
 * at runtime.
 *
 * Rows are populated by `php artisan psgc:import`.
 */
class CreateTblPsgcBarangaysTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('tbl_psgc_barangays')) {
            return;
        }

        Schema::create('tbl_psgc_barangays', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('psgc_code', 20)->unique();
            $table->string('region_code', 10)->nullable()->index();
            $table->string('region_name', 150)->nullable();
            $table->string('province_code', 10)->nullable()->index();
            $table->string('province_name', 150)->nullable();
            $table->string('city_municipality_code', 10)->nullable()->index();
            $table->string('city_municipality_name', 150)->nullable();
            $table->string('barangay_code', 10)->nullable();
            $table->string('barangay_name', 150)->nullable();
            // Pre-formatted "Barangay, City/Municipality, Province, Region"
            $table->string('full_address', 500);
            $table->timestamps();

            // Composite index covering the LIKE-prefix search the controller uses.
            $table->index('full_address');
            $table->index('barangay_name');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_psgc_barangays');
    }
}
