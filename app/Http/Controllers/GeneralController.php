<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Http\Requests\StoreGeneralSettingEditFormRequest;
use App\Setting;
use App\User;
use DB;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // general settings form
    public function index()
    {
        $country = DB::table('tbl_countries')->get()->toArray();
        $state = [];
        $city = [];
        // $settings_data = DB::table('tbl_settings')->first();

        $settings_data = Setting::first();
        if ($settings_data != null || $settings_data != '') {
            if ($settings_data->country_id != null) {
                $state = DB::table('tbl_states')->where('country_id', '=', $settings_data->country_id)->get()->toArray();
            }

            if ($settings_data->state_id != null) {
                $city = DB::table('tbl_cities')->where('state_id', '=', $settings_data->state_id)->get()->toArray();
            }
        }

        return view('general_setting.list', compact('settings_data', 'country', 'state', 'city'));
    }

    // quotation setting
    public function quotationSetting(Request $request)
    {
        if ($request->isMethod('post')) {
            // Handle form submission (POST request)
            $request->validate([
                'terms_and_condition_text' => 'nullable|string', // validation rules
            ]);

            $termsCondition = $request->input('terms_and_condition_text');

            // Retrieve the first row or create a new one if not exists
            $settings = Setting::firstOrNew();
            $settings->terms_and_condition = $termsCondition;
            $settings->save();

            return redirect('/setting/quotation_setting/list')->with('message', 'Your Terms and Condition Submitted Successfully');
            //  ->with('terms_and_condition', $termsCondition);
        }

        // Handle GET request
        $settings = Setting::first();
        $termsCondition = $settings ? $settings->terms_and_condition : '';

        return view('quotation_setting.list')->with('terms_and_condition', $termsCondition);
    }

    public function loginSettingStore(Request $request)
    {
        $enable_customer_login = $request->input('enable_customer_login') === 'yes' ? 1 : 0;

        // Set is_mobile: 1 if "yes" (Email Login), otherwise 0 (Mobile Login)
        $is_mobile = $request->input('customer_email_login') === 'yes' ? 1 : 0;

        $data = Setting::find(1);
        $data->customer_login = $enable_customer_login;
        $data->is_mobile = $is_mobile;
        $data->save();

        return redirect('/setting/customerOnboarding/list')->with('message', 'Customer Onboarding Settings Updated Successfully');
    }

    // general settings store
    public function store(StoreGeneralSettingEditFormRequest $request)
    {

        $settings_data = DB::table('tbl_settings')->first();

        $logo = $settings_data->logo_image;
        $cover = $settings_data->cover_image;

        $sys_name = $request->System_Name;
        $strt_year = $request->start_year;
        $ph_no = $request->Phone_Number;
        $email = $request->Email;
        $coutry = $request->country_id;
        $state = $request->state_id;
        $city = $request->city;
        $paypal_id = $request->Paypal_Id ?? null;
        $address = $request->address;

        $data = Setting::find(1);
        $data->address = $address;
        $data->system_name = $sys_name;
        $data->starting_year = $strt_year;
        $data->phone_number = $ph_no;
        $data->email = $email;
        $data->city_id = $city;
        $data->state_id = $state;
        $data->country_id = $coutry;

        $useradmin = User::find(1);      // update admin mobile & country in user table for use mobile login
        $useradmin->mobile_no = $ph_no;
        $useradmin->country_id = $coutry;
        $useradmin->branch_id = 1;
        $useradmin->save();

        $Logo_Image = $request->Logo_Image;
        if ($Logo_Image) {
            $file = $Logo_Image;
            $extension = $file->getClientOriginalExtension();
            $filename = time().'.'.$extension;
            $file->move(public_path().'/general_setting/', $filename);
            $data->logo_image = $filename;
        }

        $Cover_Image = $request->Cover_Image;
        if ($Cover_Image) {
            $file2 = $Cover_Image;
            $extension1 = $file2->getClientOriginalExtension();
            $filename1 = time().'.'.$extension1;
            $file2->move(public_path().'/general_setting/', $filename1);
            $data->cover_image = $filename1;
        }

        $data->paypal_id = $paypal_id;

        $data->save();

        $branch = Branch::find(1);
        $branch->contact_number = $ph_no;
        $branch->branch_email = $email;
        $branch->branch_address = $address;
        $branch->country_id = $coutry;
        $branch->state_id = $state;
        $branch->city_id = $city;
        if ($Logo_Image) {
            $branch->branch_image = $filename;
        }
        $branch->save();

        return redirect('/setting/general_setting/list')->with('message', 'General Settings Updated Successfully');
    }
}
