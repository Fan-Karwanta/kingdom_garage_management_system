<?php

namespace App\Http\Middleware;

use App\Helpers\ComposerHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DiscoverPackagesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if ($request->is('addons') || $request->routeIs('addons')) {
            try {
                $packagedir = base_path('packages');
                //  if(is_dir($packagedir) && count(scandir($packagedir)) > 2){
                $this->discoverPackages();
                // }
            } catch (\Throwable $e) {

                \Log::error('Package discovery error: '.$e->getMessage(), [
                    'trace' => $e->getTraceAsString(),
                ]);
                session()->flash('package_error', 'Package discovery failed: '.$e->getMessage());

                return $next($request);
            }
        }

        return $next($request);
    }

    /**
     * Discover and register packages from the "packages" directory
     */
    private function discoverPackages()
    {
        // throw new \Exception('diew');
        $basePackagesPath = base_path('packages');
        $packageDirectories = [];

        // Scan vendors inside /packages
        if (is_dir($basePackagesPath)) {
            $vendors = scandir($basePackagesPath);

            foreach ($vendors as $vendor) {
                if ($vendor !== '.' && $vendor !== '..') {
                    $packageDirectories[] = 'packages/'.$vendor;
                }
            }
        }

        $namespaces = [];
        $storedProviders = ComposerHelper::getStoredProviders();

        foreach ($packageDirectories as $packageDir) {
            $fullPath = base_path($packageDir);

            if (! is_dir($fullPath)) {
                continue;
            }

            $packages = scandir($fullPath);

            foreach ($packages as $package) {
                if ($package !== '.' && $package !== '..') {
                    $packagePath = $fullPath.'/'.$package;
                    $srcPath = $packagePath.'/src';

                    if (is_dir($srcPath)) {
                        $baseNamespace = ucfirst(basename($packageDir));
                        $namespace = "{$baseNamespace}\\".ucfirst($package).'\\';
                        $relativePath = "{$packageDir}/{$package}/src/";

                        // Add to namespaces regardless of whether provider exists
                        // This ensures the autoloader can find classes in these namespaces
                        $namespaces[$namespace] = $relativePath;
                    }
                }
            }
        }
        // Only proceed if we found namespaces that need to be registered
        if (! empty($namespaces)) {
            // Log::info('Namespaces to register:', $namespaces);

            // Update the autoloader with all discovered namespaces
            $result = ComposerHelper::updateComposerAutoload($namespaces);
            // Log::info('Package discovery result: ' . json_encode($result));
        }
    }
}
