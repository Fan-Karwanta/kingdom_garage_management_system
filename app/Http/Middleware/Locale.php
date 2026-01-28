<?php

namespace App\Http\Middleware;

use App;
use Auth;
use Closure;
use DB;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    // public function handle($request, Closure $next)
    // {
    //     //if(!empty(Auth::user()->id))
    //    if(!empty(auth()->getUser()->email))
    //     {
    //         $lan = DB::table('users')->where('id', Auth::user()->id)->first();
    //         if(!empty($lan))
    //         {
    //             $locale = $lan->language;
    //         }
    //         else
    //         {
    //             $locale = 'en';
    //         }
    //        App::setLocale($locale);
    //     }
    //    return $next($request);
    // }

    /* Able to stranslation in login page or without auth pages- like frontend booking */
    public function handle($request, Closure $next)
    {
        // if(!empty(Auth::user()->id))
        if (file_exists('installed.txt')) {
            if (Auth::check()) {
                $lan = DB::table('users')->where('id', Auth::user()->id)->first();
                $locale = $lan->language;
            } else {
                $admin = DB::table('users')->where('id', 1)->first();
                $locale = $admin->language ?? 'en';
            }

            App::setLocale($locale);
        }

        return $next($request);
    }
}
