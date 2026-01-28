<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        $nonce = base64_encode(random_bytes(16));

        // Make it available globally
        app()->instance('cspNonce', $nonce);
        view()->share('cspNonce', $nonce);
        $request->attributes->set('csp_nonce', $nonce);
        $response = $next($request);

        // $csp = "default-src 'self'; img-src 'self' data: https://cdn.example.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com data:; script-src 'self' 'unsafe-eval' 'nonce-$nonce' https://js.stripe.com; frame-src https://js.stripe.com; object-src 'none'; frame-ancestors 'none'; base-uri 'self'; connect-src 'self' https://api.stripe.com; media-src 'self';";
        $csp = "default-src 'self'; img-src 'self' data: https://cdn.example.com https://test-aiservice.yaraamanager.com https://niftyhms.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://www.gstatic.com https://test-aiservice.yaraamanager.com; font-src 'self' https://fonts.gstatic.com data:; script-src 'self' 'unsafe-eval' 'nonce-$nonce' https://js.stripe.com https://test-aiservice.yaraamanager.com https://www.gstatic.com https://www.google.com; frame-src https://js.stripe.com; object-src 'none'; frame-ancestors 'none'; base-uri 'self'; connect-src 'self' https://api.stripe.com https://test-aiservice.yaraamanager.com; media-src 'self' https://test-aiservice.yaraamanager.com;";

        // remove whitespace/newlines
        $csp = preg_replace('/\s+/', ' ', trim($csp));

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
