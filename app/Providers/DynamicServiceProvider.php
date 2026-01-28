<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class DynamicServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $providers = $this->getStoredProviders();

        if (! empty($providers)) {
            foreach ($providers as $provider) {
                if (class_exists($provider)) {
                    $this->app->register($provider);

                } else {
                    // Log::error("Provider class not found: $provider");
                }
            }
        } else {
            // Log::info("No dynamic providers found to register.");
        }
    }

    /**
     * Get stored providers from the dynamically generated file.
     */
    private function getStoredProviders()
    {
        $path = storage_path('app/dynamic_providers.php');

        return File::exists($path) ? include $path : [];
    }
}
