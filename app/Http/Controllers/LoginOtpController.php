<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

require_once base_path('packages/mjsms/smsaddon/vendor/autoload.php');

class LoginOtpController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile_no' => 'required',
        ]);

        $admin = DB::table('users')->where('id', 1)->first(); // or use role/admin logic
        $countryCode = DB::table('tbl_countries')->where('id', $admin->country_id)->value('phonecode');

        \Log::info($countryCode);
        if (! $countryCode) {
            return back()->withErrors(['mobile_no' => 'Admin country phone code not found.']);
        }

        $userExists = DB::table('users')->where('mobile_no', $request->mobile_no)->exists();
        \Log::info($userExists);
        if (! $userExists) {
            return back()->withErrors(['mobile_no' => 'Mobile number not found.']);
        }
        $formattedMobile = '+'.$countryCode.$request->mobile_no;

        $otp = rand(100000, 999999);
        Session::put('otp', $otp);
        Session::put('otp_phone', $request->mobile_no);
        $mobile = $formattedMobile;
        $message = "Your OTP is: $otp";

        // --- 1. Try Twilio ---
        $twilioDetails = DB::table('sms_settings')->where('gateway_name', 'twilio')->first();
        if (! empty($twilioDetails) && $twilioDetails->is_active == '1') {
            try {
                $twilioCredentials = json_decode($twilioDetails->credentials);
                $sid = $twilioCredentials->twilio_sid;
                $token = $twilioCredentials->twilio_token;
                $twilioNumber = $twilioCredentials->twilio_number;

                $twilio = new \Twilio\Rest\Client($sid, $token);
                $twilio->messages->create($mobile, [
                    'from' => $twilioNumber,
                    'body' => $message,
                ]);
                Session::put('otp_sent');

                return back()->with('otp_sent', true)->with('message', 'OTP sent successfully via Twilio!');
            } catch (\Exception $e) {
                Log::error('Twilio OTP send failed: '.$e->getMessage());
            }
        }

        // --- 2. Try MSG91 ---
        $msg91Details = DB::table('sms_settings')->where('gateway_name', 'msg91')->first();
        if (! empty($msg91Details) && $msg91Details->is_active == '1') {
            try {
                $msg91Credentials = json_decode($msg91Details->credentials);
                $authKey = $msg91Credentials->msg91_auth_key;
                $senderId = $msg91Credentials->msg91_sender_id;
                $dlt_te_id = $msg91Credentials->msg91_dlt_te_id;

                $smsData = [
                    'message' => $message,
                    'to' => [$mobile],
                ];

                if (substr($mobile, 0, 3) === '+91') {
                    $smsData['DLT_TE_ID'] = $dlt_te_id;
                }

                $client = new \GuzzleHttp\Client;
                $response = $client->post('https://api.msg91.com/api/v2/sendsms', [
                    'headers' => [
                        'authkey' => $authKey,
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'sender' => $senderId,
                        'route' => 4,
                        'sms' => [$smsData],
                    ],
                ]);
                Session::put('otp_sent');

                return back()->with('otp_sent', true)->with('message', 'OTP sent successfully via MSG91!');
            } catch (\Exception $e) {
                Log::error('MSG91 OTP send failed: '.$e->getMessage());
            }
        }

        // --- 3. Try Clickatell ---
        $clickatellDetails = DB::table('sms_settings')->where('gateway_name', 'clickatell')->first();

        if (! empty($clickatellDetails) && $clickatellDetails->is_active == '1') {
            try {
                $clickatellCredentials = json_decode($clickatellDetails->credentials);
                $apiKey = $clickatellCredentials->clickatell_api_key;

                $response = Http::get('https://platform.clickatell.com/messages/http/send', [
                    'apiKey' => $apiKey,
                    'to' => $mobile,
                    'content' => $message,
                ]);
                Session::put('otp_sent');
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'OTP sent successfully!',
                    ]);
                }

                return back()->with('otp_sent', true)->with('message', 'OTP sent successfully via Clickatell!');
            } catch (\Exception $e) {
                Log::error('Clickatell OTP send failed: '.$e->getMessage());
            }
        }

        // --- All services failed ---
        return back()->with('error', 'Failed to send OTP. No SMS service is active or all attempts failed.');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required',
        ]);

        if ($request->otp == Session::get('otp')) {
            $user = User::where('mobile_no', Session::get('otp_phone'))->first();
            Auth::login($user);
            Session::forget(['otp', 'otp_phone']);

            return redirect()->route('dashboard');
        }

        return back()->withErrors(['otp' => 'Invalid OTP']);
    }
}
