<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Provides PSGC address autocomplete for the single-field address UI.
 *
 * The data is served from the local tbl_psgc_barangays table (populated by
 * `php artisan psgc:import`) so address entry never depends on an external
 * API at runtime.
 */
class PsgcController extends Controller
{
    public function __construct()
    {
        // Search must be accessible to anyone filling in a form, including
        // unauthenticated frontend booking forms. Match the existing
        // getstatefromcountry / getcityfromstate routes which also bypass auth.
        $this->middleware('auth')->except('search');
    }

    /**
     * GET /psgc/search?q=...
     *
     * Returns up to {search_limit} barangay matches as JSON:
     *   [{ psgc_code, full_address, barangay_name, city_municipality_name,
     *      province_name, region_name }]
     *
     * The search matches against full_address (which contains barangay,
     * city/municipality, province, and region) so typing any part of the
     * address yields relevant results.
     */
    public function search(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        $limit = (int) config('services.psgc.search_limit', 20);
        $minLen = (int) config('services.psgc.min_query_length', 2);

        if ($q === '' || mb_strlen($q) < $minLen) {
            return response()->json([]);
        }

        // Token-based matching: split the query into words and require EVERY
        // token to appear somewhere in full_address (as a substring). This lets
        // "digos city" match "Aplaya, City Of Digos (Capital), Davao del Sur,
        // Region XI (Davao Region)" because both "digos" and "city" are present,
        // even though neither is a prefix of full_address.
        //
        // We also keep a prefix-OR branch so short single-word queries (e.g.
        // "davao") still hit the index for fast prefix matches.
        $tokens = preg_split('/\s+/', $q, -1, PREG_SPLIT_NO_EMPTY);
        $tokens = array_slice($tokens, 0, 6); // cap to avoid huge queries

        $query = DB::table('tbl_psgc_barangays');

        if (count($tokens) === 1) {
            // Single token — prefix match on barangay or full_address, plus
            // substring match for flexibility.
            $word = $tokens[0];
            $query->where(function ($q2) use ($word) {
                $q2->where('full_address', 'LIKE', $word.'%')
                   ->orWhere('barangay_name', 'LIKE', $word.'%')
                   ->orWhere('city_municipality_name', 'LIKE', $word.'%')
                   ->orWhere('full_address', 'LIKE', '%'.$word.'%');
            });
        } else {
            // Multi-token — every token must appear in full_address (substring).
            // This handles "digos city" → both "digos" and "city" present.
            $query->where(function ($q2) use ($tokens) {
                foreach ($tokens as $token) {
                    $q2->where('full_address', 'LIKE', '%'.$token.'%');
                }
            });
        }

        $results = $query->orderBy('barangay_name')
            ->limit($limit)
            ->get([
                'psgc_code',
                'full_address',
                'barangay_name',
                'city_municipality_name',
                'province_name',
                'region_name',
            ]);

        return response()->json($results);
    }
}
