<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class CustomValidationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('custom_email', function ($attribute, $value, $parameters, $validator) {
            // Check if the email contains a dot (.) in the domain part
            return strpos($value, '@') !== false && strpos($value, '.') !== false;
        });
    }
}
