<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds PSGC address columns to the three tables that store addresses:
 *   - users        (customers, employees, suppliers, accountants, support staff, branch admins)
 *   - branches
 *   - tbl_settings (company/garage info)
 *
 * The legacy country_id / state_id / city_id columns are KEPT (not dropped) so
 * existing rows still render via the getCountryName/getStateName/getCityName
 * helpers and so PDF/invoice templates keep working during the transition.
 *
 * psgc_code   : 10-digit PSA PSGC barangay code (self-contained — encodes
 *               region/province/city-municipality/barangay).
 * full_address: formatted "Barangay, City/Municipality, Province, Region"
 *               string used for fast display without joins.
 */
class AddPsgcAddressColumns extends Migration
{
    public function up()
    {
        foreach (['users', 'branches', 'tbl_settings'] as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            Schema::table($table, function (Blueprint $t) use ($table) {
                if (! Schema::hasColumn($table, 'psgc_code')) {
                    $t->string('psgc_code', 20)->nullable()->after(
                        $table === 'branches' ? 'city_id' : ($table === 'tbl_settings' ? 'country_id' : 'city_id')
                    );
                }
                if (! Schema::hasColumn($table, 'full_address')) {
                    $t->text('full_address')->nullable()->after('psgc_code');
                }
            });
        }
    }

    public function down()
    {
        foreach (['users', 'branches', 'tbl_settings'] as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            Schema::table($table, function (Blueprint $t) use ($table) {
                if (Schema::hasColumn($table, 'full_address')) {
                    $t->dropColumn('full_address');
                }
                if (Schema::hasColumn($table, 'psgc_code')) {
                    $t->dropColumn('psgc_code');
                }
            });
        }
    }
}
