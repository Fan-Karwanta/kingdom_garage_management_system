<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $user = Auth::user();

            if ($user->soft_delete == 1) {
                Auth::logout();
                return Response::json([
                    'status' => false,
                    'code' => 401,
                    'message' => 'Account has been deleted',
                    'data' => null,
                ], 401);
            }

            $response = [
                'status' => true,
                'code' => 200,
                'message' => 'Login Successfully',
                'data' => [
                    'Id' => $user->id,
                    'Name' => $user->name.' '.$user->lastname,
                    'Role' => $user->role,
                    'image' => $user->image ? url('public/admin/'.$user->image) : null,
                ],
            ];

            return Response::json($response, 200);
        }

        return Response::json([
            'status' => false,
            'code' => 401,
            'message' => 'Invalid credentials',
            'data' => null,
        ], 401);
    }
}
