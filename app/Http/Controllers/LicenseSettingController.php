<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LicenseSettingController extends Controller 
{
    public function index()
    {
        $settings_data = DB::table('tbl_settings')->first();
        
        return view('license_setting.list', compact('settings_data'));
    }

    /**
     * Send OTP for license reset
     */
 public function sendLicenseOtp(Request $request)
{
    try {
        // Check if domain is localhost
        $domain_name = $_SERVER['SERVER_NAME'];
        if (in_array(strtolower($domain_name), ['localhost', '127.0.0.1', '::1']) ||
            strpos(strtolower($domain_name), 'localhost') !== false) {
            return response()->json([
                'error' => true,
                'message' => 'License cannot be reset on localhost domain. Please use a live domain.'
            ], 400);
        }

        $request->validate([
            'email' => 'required|email',
            'pkey' => 'required',
        ]);

        $email = $request->email;
        $pkey = $request->pkey;

        $url = 'https://license.dasinfomedia.com/admin/api/license/send-otp';
        $payload = [
            'email' => $email,
            'pkey' => $pkey,
        ];

        // Initialize cURL
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false, // 🧩 Disable SSL verification if needed
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return response()->json([
                'error' => true,
                'message' => 'cURL Error: ' . $error_msg
            ], 500);
        }

        curl_close($ch);

        $data = json_decode($response, true);

        // Handle JSON response
        if (isset($data['error']) && $data['error'] === false) {
            Session::put('license_reset_email', $email);
            Session::put('license_reset_pkey', $pkey);

            return response()->json([
                'error' => false,
                'message' => $data['message'] ?? 'OTP sent to your email successfully'
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => $data['message'] ?? 'Failed to send OTP'
            ]);
        }

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'error' => true,
            'message' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all())
        ], 422);
    } catch (\Exception $e) {
        \Log::error('License OTP Send Error: ' . $e->getMessage());

        return response()->json([
            'error' => true,
            'message' => 'Unexpected error: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Verify OTP for license reset
     */
 public function verifyLicenseOtp(Request $request)
{
    try {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6',
        ]);

        $email = $request->email;
        $otp = $request->otp;
        $pkey = Session::get('license_reset_pkey');

        // Verify email matches the one from send OTP step
        if (Session::get('license_reset_email') !== $email) {
            return response()->json([
                'error' => true,
                'message' => 'Email mismatch. Please start the process again.'
            ], 400);
        }

        $url = 'https://license.dasinfomedia.com/admin/api/license/verify-otp';
        $payload = [
            'email' => $email,
            'otp'   => $otp,
            'pkey'  => $pkey,
        ];

        // Initialize cURL
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false, // 🧩 helps if SSL cert not trusted locally
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return response()->json([
                'error' => true,
                'message' => 'cURL Error: ' . $error_msg
            ], 500);
        }

        curl_close($ch);

        $data = json_decode($response, true);

        // Check API response
        if (isset($data['error']) && $data['error'] === false) {
            // ✅ Success
            Session::forget('license_reset_email');
            Session::forget('license_reset_pkey');

            \Log::info('License reset successful for email: ' . $email);

            return response()->json([
                'error' => false,
                'message' => $data['message'] ?? 'License has been reset successfully'
            ]);
        } else {
            // ❌ OTP invalid or expired
            return response()->json([
                'error' => true,
                'message' => $data['message'] ?? 'Invalid or expired OTP'
            ]);
        }
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'error' => true,
            'message' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all())
        ], 422);
    } catch (\Exception $e) {
        \Log::error('License OTP Verify Error: ' . $e->getMessage());

        return response()->json([
            'error' => true,
            'message' => 'Unexpected error: ' . $e->getMessage()
        ], 500);
    }
}

}