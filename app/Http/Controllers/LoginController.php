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
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // The "email" parameter accepts a username or an email.
        $login = $credentials['email'];
        $userQuery = User::where('email', '=', $login);
        if (\Schema::hasColumn('users', 'username')) {
            $userQuery->orWhere('username', '=', $login);
        }
        $matchedUser = $userQuery->get()
            ->first(function ($user) use ($credentials) {
                return Auth::validate(['id' => $user->id, 'password' => $credentials['password']]);
            });

        if ($matchedUser) {
            Auth::login($matchedUser);
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
