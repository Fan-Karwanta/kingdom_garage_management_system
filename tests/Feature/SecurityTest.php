<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    /*
    |--------------------------------------------------------------------------
    | Cache-Clear Route Protection Tests
    |--------------------------------------------------------------------------
    */

    public function test_cache_clear_routes_require_auth()
    {
        $routes = ['/clear-cache', '/route-cache', '/view-clear', '/config-cache'];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $this->assertNotEquals(200, $response->status(), "Route {$route} should not be accessible without auth");
        }
    }

    public function test_clear_cache_returns_302_or_401_without_auth()
    {
        $response = $this->get('/clear-cache');
        $this->assertContains($response->status(), [302, 401, 403]);
    }

    /*
    |--------------------------------------------------------------------------
    | CSRF Protection Tests
    |--------------------------------------------------------------------------
    */

    public function test_password_forgot_route_is_protected()
    {
        $response = $this->post('/password/forgot', [
            'email' => 'test@example.com',
        ]);

        // Should not return 200 — either CSRF (419), validation (302), or rate limited
        $this->assertNotEquals(200, $response->status(), 'Password forgot route should not be freely accessible');
    }

    public function test_passwordchange_route_is_protected()
    {
        $response = $this->post('/passwordchange', []);
        $this->assertNotEquals(200, $response->status(), 'Password change route should not be freely accessible');
    }

    /*
    |--------------------------------------------------------------------------
    | Login Validation Tests
    |--------------------------------------------------------------------------
    */

    public function test_app_login_requires_email_field()
    {
        $response = $this->withSession([])->get('/app_login', [
            'password' => 'password',
            '_token' => csrf_token(),
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_app_login_requires_valid_email_format()
    {
        $response = $this->withSession([])->get('/app_login', [
            'email' => 'not-an-email',
            'password' => 'password',
            '_token' => csrf_token(),
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_app_login_requires_password_field()
    {
        $response = $this->withSession([])->get('/app_login', [
            'email' => 'test@example.com',
            '_token' => csrf_token(),
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Tests
    |--------------------------------------------------------------------------
    */

    public function test_app_login_rate_limiting()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->withSession([])->get('/app_login', [
                'email' => 'test@example.com',
                'password' => 'wrong',
                '_token' => csrf_token(),
            ]);
        }

        $response = $this->withSession([])->get('/app_login', [
            'email' => 'test@example.com',
            'password' => 'wrong',
            '_token' => csrf_token(),
        ]);

        $this->assertEquals(429, $response->status());
    }

    public function test_password_forgot_rate_limiting()
    {
        for ($i = 0; $i < 3; $i++) {
            $this->post('/password/forgot', [
                'email' => 'test@example.com',
                '_token' => csrf_token(),
            ]);
        }

        $response = $this->post('/password/forgot', [
            'email' => 'test@example.com',
            '_token' => csrf_token(),
        ]);

        $this->assertEquals(429, $response->status());
    }

    /*
    |--------------------------------------------------------------------------
    | Password Reset Validation Tests
    |--------------------------------------------------------------------------
    */

    public function test_password_forgot_validates_email()
    {
        $response = $this->post('/password/forgot', [
            'email' => 'not-an-email',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_password_forgot_requires_email_field()
    {
        $response = $this->post('/password/forgot', []);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_password_change_requires_password()
    {
        $response = $this->post('/passwordchange', [
            'email' => 'test@example.com',
            'token' => 'fake-token',
            '_token' => csrf_token(),
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_password_change_requires_matching_confirmation()
    {
        $response = $this->post('/passwordchange', [
            'email' => 'test@example.com',
            'token' => 'fake-token',
            'password' => 'Password1',
            'password_confirmation' => 'DifferentPassword1',
            '_token' => csrf_token(),
        ]);

        $response->assertSessionHasErrors(['password_confirmation']);
    }

    /*
    |--------------------------------------------------------------------------
    | Security Headers Tests
    |--------------------------------------------------------------------------
    */

    public function test_response_contains_x_content_type_options_header()
    {
        $response = $this->get('/login');
        $this->assertEquals('nosniff', $response->headers->get('X-Content-Type-Options'));
    }

    public function test_response_contains_x_frame_options_header()
    {
        $response = $this->get('/login');
        $this->assertEquals('DENY', $response->headers->get('X-Frame-Options'));
    }

    public function test_response_contains_referrer_policy_header()
    {
        $response = $this->get('/login');
        $this->assertEquals('strict-origin-when-cross-origin', $response->headers->get('Referrer-Policy'));
    }

    /*
    |--------------------------------------------------------------------------
    | API Route Protection Tests
    |--------------------------------------------------------------------------
    */

    public function test_api_sidemenu_requires_auth()
    {
        $response = $this->getJson('/api/sidemenu');
        $this->assertNotEquals(200, $response->status(), 'API /sidemenu should require authentication');
    }

    public function test_api_user_route_requires_auth()
    {
        $response = $this->getJson('/api/user');
        $this->assertNotEquals(200, $response->status(), 'API /user should require authentication');
    }

    /*
    |--------------------------------------------------------------------------
    | Frontend Route Validation Tests
    |--------------------------------------------------------------------------
    */

    public function test_frontend_booking_route_requires_auth()
    {
        $response = $this->post('/', [
            '_token' => csrf_token(),
        ]);

        $this->assertNotEquals(200, $response->status(), 'Frontend booking POST should require authentication');
    }

    /*
    |--------------------------------------------------------------------------
    | Environment Configuration Tests
    |--------------------------------------------------------------------------
    */

    public function test_app_debug_is_false_in_testing()
    {
        $this->assertFalse(config('app.debug'), 'APP_DEBUG should be false in testing/production');
    }

    public function test_app_key_is_set()
    {
        $this->assertNotEmpty(config('app.key'), 'APP_KEY must be set');
    }
}
