<?php

namespace App\Http\Controllers;

use App\MailNotification;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class Mailcontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // mail form
    public function index()
    {
        $mailformat = MailNotification::get();

        return view('mail.mail', compact('mailformat'));
    }

    // mail update
    public function emailupadte($id, Request $request)
    {
        $emailformat = MailNotification::find($id);

        $emailformat->subject = $request->subject;
        $emailformat->send_from = $request->send_from;
        $emailformat->notification_text = $request->notification_text;
        $emailformat->is_send = $request->is_send;
        $emailformat->save();

        return redirect('/mail/mail')->with('message', 'Email Template Updated Successfully');
    }

    // mail for user
    public function user()
    {
        return view('mail.user');
    }

    // mail for sales
    public function sales()
    {
        return view('mail.sales');
    }

    // mail for service
    public function services()
    {
        return view('mail.service');
    }

    // mail setting
    public function setting()
    {
        // Clear the config cache to reload the latest .env values
        \Artisan::call('config:clear');
        \Artisan::call('cache:clear');  // Clears cache in case it affects form inputs
        // Use the env() function to get the latest values from the .env file
        $configData = [
            'MAIL_DRIVER' => env('MAIL_DRIVER'),
            'MAIL_HOST' => env('MAIL_HOST'),
            'MAIL_PORT' => env('MAIL_PORT'),
            'MAIL_USERNAME' => env('MAIL_USERNAME'),
            'MAIL_PASSWORD' => env('MAIL_PASSWORD'),
            'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION'),
            'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS'),
        ];
        /*	$file = base_path('.env');
            $content = file_get_contents($file);

            $configKeys = [
                'MAIL_DRIVER',
                'MAIL_HOST',
                'MAIL_PORT',
                'MAIL_USERNAME',
                'MAIL_PASSWORD',
                'MAIL_ENCRYPTION',
                'MAIL_FROM_ADDRESS',
            ];

            $configData = [];

            foreach ($configKeys as $key) {
                preg_match("/$key=(.*)/", $content, $matches);
                $configData[$key] = isset($matches[1]) ? $matches[1] : '';
            }  */

        return view('email_setting.list', compact('configData'));
    }

    public function settingStore(Request $request)
    {
        $file = base_path('.env');
        $content = file_get_contents($file);
        $request->validate([
            'MAIL_FROM_ADDRESS' => 'required|email',
        ], [
            'MAIL_FROM_ADDRESS.email' => 'The Mail From Address must be a valid email address.',
        ]);
        $configKeys = [
            'MAIL_DRIVER',
            'MAIL_HOST',
            'MAIL_PORT',
            'MAIL_USERNAME',
            'MAIL_PASSWORD',
            'MAIL_ENCRYPTION',
            'MAIL_FROM_ADDRESS',
        ];
        foreach ($configKeys as $key) {
            if ($request->has($key)) {
                $value = $request->input($key);
                $content = preg_replace("/$key=(.*)/", "$key=$value", $content);
            }
        }

        $status = file_put_contents($file, $content);
        $email = $request->input('MAIL_FROM_ADDRESS');
        if (! empty($email)) {
            // Update tbl_mail_notifications with new email
            DB::table('tbl_mail_notifications')->update(['send_from' => $email]);
        }

        return redirect('setting/email_setting/list')->with('message', 'Email Settings Updated Successfully');   //
    }

    public function sendTestEmail(Request $request)
    {
        // Validate the input email
        $request->validate([
            'Test_mail' => 'required|email',
        ]);

        $email = $request->input('Test_mail', 'test@example.com'); // Default email if not provided
        $mail_sub = 'Testing Email Notification';
        $emailformat = DB::table('tbl_mail_notifications')->where('notification_for', '=', 'done_service_invoice')->first();
        $mail_send_from = $emailformat->send_from;

        try {
            Mail::raw('This is a test email sending for Testing Purpose...', function ($message) use ($email, $mail_sub, $mail_send_from) {
                $message->to($email)
                    ->subject($mail_sub)
                    ->from($mail_send_from);
            });

            // \Log::info('Testing email sent to: ' . $email);
        } catch (\Exception $e) {
            // \Log::error('Error sending Testing email: ' . $e->getMessage());
            return response()->view('errors.smtp_error', [], 500);
        }

        return redirect('setting/email_setting/list')->with('message', 'Test email sent successfully');
    }
}
