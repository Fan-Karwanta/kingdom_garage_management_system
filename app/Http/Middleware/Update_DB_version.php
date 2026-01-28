<?php

namespace App\Http\Middleware;

use App;
use Closure;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Update_DB_version
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $userLanguage = DB::table('users')->where('id', Auth::user()->id)->value('language');

            if (! empty($userLanguage)) {
                $locale = $userLanguage;
            } else {
                $locale = 'en';
            }

            App::setLocale($locale);
        }

        $currentRoute = request()->getPathInfo();

        $checkRoute = ['/updateDB', '/logout', '/Update_version'];

        if (in_array($currentRoute, $checkRoute) || ! Auth::check()) {
            return $next($request);
        } else {
            if (isAdmin(Auth::user()->id)) {
                $setting = DB::table('tbl_settings')->first();
                $result = version_compare($setting->version, '4.6.5', '<');
                if ($result) {
                    return redirect()->route('db_version');
                } else {
                    return $next($request);
                }
            } else {
                return $next($request);
            }
        }
    }
}
