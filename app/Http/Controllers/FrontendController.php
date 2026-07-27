<?php

namespace App\Http\Controllers;

use App\Holiday;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FrontendController extends Controller
{
    /**
     * Public front-facing entry point for the domain root.
     *
     * Guests / visitors are shown the welcoming landing + booking page.
     * Authenticated internal users continue to see their dashboard exactly
     * as before (permission enforced via the dashboard_view gate).
     */
    public function index(Request $request)
    {
        if (Auth::check()) {
            if (Gate::denies('dashboard_view')) {
                abort(403);
            }

            return app(HomeController::class)->dashboard();
        }

        return $this->landing();
    }

    /**
     * Render the customer-facing landing page with the booking calendar
     * and the existing booking form (reuses the same data as frontendBook).
     */
    public function landing()
    {
        $holiday = Holiday::orderBy('date', 'ASC')->get();

        $last_order = DB::table('tbl_services')->latest()->where('sales_id', '=', null)->get()->first();

        if (! empty($last_order)) {
            $last_full_job_number = $last_order->job_no;
            $last_job_number_digit = substr($last_full_job_number, 1);
            $new_number = 'J'.str_pad($last_job_number_digit + 1, 6, 0, STR_PAD_LEFT);
        } else {
            $new_number = 'J000001';
        }

        $code = $new_number;

        $country = DB::table('tbl_countries')->get()->toArray();

        $vehical_type = DB::table('tbl_vehicle_types')->where('soft_delete', '=', 0)->get()->toArray();
        $vehical_brand = DB::table('tbl_vehicle_brands')->where('soft_delete', '=', 0)->get()->toArray();
        $fuel_type = DB::table('tbl_fuel_types')->where('soft_delete', '=', 0)->get()->toArray();
        $model_name = DB::table('tbl_model_names')->where('soft_delete', '=', 0)->get()->toArray();
        $repairCategoryList = DB::table('table_repair_category')->where([['soft_delete', '=', 0]])->get()->toArray();

        return view('frontend.landing', compact('holiday', 'code', 'country', 'vehical_brand', 'vehical_type', 'fuel_type', 'model_name', 'repairCategoryList'));
    }
}
