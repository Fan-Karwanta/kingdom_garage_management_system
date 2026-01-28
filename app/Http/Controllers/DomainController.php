<?php

namespace App\Http\Controllers;

use App\PurchaseApp;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DomainController extends Controller
{
    // For check domain start
    public function domain()
    {
        return view('Installer.update_domain');
    }

    public function update_domain(Request $request)
    {
        $domain_name = $request->domain_name;
        $licence_key = $request->purchase_key;
        $purchase_email = $request->purchase_email;

        // License check bypassed - save settings directly
        $setting = Setting::first();
        $setting->domain_name = $domain_name;
        $setting->purchase_email = $purchase_email;
        $setting->licence_key = $licence_key;
        $setting->save();

        return redirect('/');
    }
    // For check domain end

    // For app licence start
    public function store_license(Request $request)
    {
        $app_email = $request->input('email');
        $app_url = $request->input('url');
        $app_licence_key = $request->input('licence_key');

        $purchaseApp = new PurchaseApp;
        $purchaseApp->app_email = $app_email;
        $purchaseApp->app_url = $app_url;
        $purchaseApp->app_licence_key = $app_licence_key;
        $purchaseApp->save();

        $response = [
            'status' => true,
            'code' => 200,
            'message' => 'License Added Successfully',
        ];

        return response()->json($response, 200);
    }

    public function get_license()
    {
        $setting = PurchaseApp::latest()->first();

        if ($setting) {
            $response = [
                'status' => true,
                'code' => 200,
                'message' => 'Get Licence Successfully',
                'data' => [
                    'app_email' => $setting->app_email,
                    'app_url' => $setting->app_url,
                    'app_licence_key' => $setting->app_licence_key,
                ],
            ];
        } else {
            $response = [
                'status' => false,
                'code' => 401,
                'message' => 'Record not found',
                'data' => null,
            ];
        }

        return response()->json($response, 200);
    }
    // For app licence start
}
