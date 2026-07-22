<?php

namespace App\Http\Controllers;

use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Response;
use Mail;

class RestePassword extends Controller
{
    // password reset link
    public function forgotpassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;
        $user = DB::table('users')->where('email', '=', $email)->first();

        if ($user) {
            $token = Password::createToken(User::find($user->id));
            $name = $user->name;

            $link = url('passwords/reset/'.$token.'/'.$email);
            $email_content = 'To Reset Your Password Please Click...'.$link;
            $mail_sub = 'Reset Password';
            $mail_send_from = env('MAIL_FROM_ADDRESS', 'noreply@example.com');
            $data = [
                'email' => $email,
                'name' => $name,
                'token' => $token,
                'reset_link' => $link,
            ];

            try {
                Mail::send('mail.template', $data, function ($message) use ($data, $mail_send_from) {
                    $message->from($mail_send_from, 'Reset Password');
                    $message->to($data['email'])->subject('Reset Password');
                });
            } catch (\Exception $e) {
                \Log::error('Error sending forgotpassword email: '.$e->getMessage());
            }

            $response = [
                'status' => true,
                'code' => 200,
                'message' => 'Link for password reset has been emailed to you. Please check your email.',
                'data' => null,
            ];

            return Response::json($response, 200);
        } else {
            $response = [
                'status' => false,
                'code' => 401,
                'message' => 'Please enter valid Email',
                'data' => null,
            ];

            return Response::json($response, 401);
        }
    }
}
