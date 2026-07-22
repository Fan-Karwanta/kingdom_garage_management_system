<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStripeSettingEditFormRequest;
use App\Setting;
use App\Updatekey;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;

class Timezonecontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // timezone list
    public function index()
    {
        $user = User::where('id', '=', Auth::user()->id)->first();
        $currancy = DB::table('tbl_currency_records')->get()->toArray();
        $currencies = DB::table('currencies')->get()->toArray();

        $tbl_settings = Setting::first();
        $employees = User::where([['role', 'employee'], ['soft_delete', 0]])->orderBy('id', 'DESC')->get();

        return view('timezone.list', compact('user', 'currancy', 'tbl_settings', 'currencies', 'employees'));
    }

    // currency store
    public function currancy(Request $request)
    {
        $time = $request->timezone;
        $id = Auth::user()->id;
        $users = DB::table('users')->where('id', '=', $id)->first();
        DB::table('users')->where('id', $id)->update(['timezone' => $time]);

        $lang = $request->language;

        $id = Auth::user()->id;
        $users = DB::table('users')->where('id', '=', $id)->first();
        $language = $users->language;
        DB::table('users')->where('id', $id)->update(['language' => $lang]);

        if ($lang == 'ar') {
            $id = Auth::user()->id;
            DB::table('users')->where('id', $id)->update(['gst_no' => 'rtl']);
        } else {
            $id = Auth::user()->id;
            DB::table('users')->where('id', $id)->update(['gst_no' => 'ltr']);
        }

        $date = $request->dateformat;
        if (! empty($date)) {
            $dateformat = DB::table('tbl_settings')->first();
            $first = $dateformat->id;
            DB::table('tbl_settings')->where('id', $first)->update(['date_format' => $date]);
        }

        $Currency = $request->Currency;
        if (! empty($Currency)) {
            $Currencyformat = DB::table('tbl_settings')->first();
            $id = $Currencyformat->id;
            DB::table('tbl_settings')->where('id', $id)->update(['currancy' => $Currency]);
        }

        $frontend_booking = $request->frontend_service;
        $tbl_settings = DB::table('tbl_settings')->first();
        $id = $tbl_settings->id;
        if ($frontend_booking == 'on') {
            $frontend_service = 1;
            $default_emp = $request->default_emp;
            $default_charge = $request->default_charge;
            $default_password = $request->default_password;
            DB::table('tbl_settings')->where('id', $id)->update(['frontend_service' => $frontend_service, 'default_emp' => $default_emp, 'default_charge' => $default_charge, 'default_password' => $default_password]);
        } else {
            $frontend_service = 0;
            $default_emp = null;
            $default_charge = null;
            $default_password = null;
            DB::table('tbl_settings')->where('id', $id)->update(['frontend_service' => $frontend_service, 'default_emp' => $default_emp, 'default_charge' => $default_charge, 'default_password' => $default_password]);
        }

        $edit_service = $request->edit_service;
        $service = DB::table('tbl_settings')->first();
        $id = $service->id;
        if ($edit_service == 'on') {
            DB::table('tbl_settings')->where('id', $id)->update(['edit_service' => '1']);
        } else {
            DB::table('tbl_settings')->where('id', $id)->update(['edit_service' => '0']);
        }

        return redirect('/setting/timezone/list')->with('message', 'Other Settings Updated Successfully');
    }

    // Stripe key list
    public function stripeList()
    {
        $settings_data = Updatekey::first();

        return view('stripe_setting.list', compact('settings_data'));
    }

    // Stripe Key Update
    public function stripeStore(StoreStripeSettingEditFormRequest $request)
    {
        $updateStripeKey = Updatekey::where('stripe_id', $request->stripe_id)->update([
            'secret_key' => $request->secret_key,
            'publish_key' => $request->publish_key,

        ]);

        if ($updateStripeKey) {
            return redirect('/setting/stripe/list')->with('message', 'Stripe Settings Updated Successfully');
        } else {
            return redirect('/setting/stripe/list')->with('error', 'Not Updated');
        }
    }
}
