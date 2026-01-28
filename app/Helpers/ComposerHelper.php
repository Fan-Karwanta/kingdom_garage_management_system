<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use RecursiveCallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ComposerHelper
{
    public static function updateComposerAutoload(array $namespaces)
    {

        try {
            // Step 1: Update composer.json
            $result = self::updateComposerJson($namespaces);
            if ($result['status'] === 'error') {
                return $result;
            }

            // Step 2: Update autoload_psr4.php
            $result = self::updateAutoloadPsr4($namespaces);
            if ($result['status'] === 'error') {
                return $result;
            }

            // Step 3: Update autoload_classmap.php
            $result = self::updateAutoloadClassmap();
            if ($result['status'] === 'error') {
                return $result;
            }

            // Step 4: Update autoload_static.php
            $result = self::updateAutoloadStatic($namespaces);
            if ($result['status'] === 'error') {
                return $result;
            }

            self::registerServiceProviders($namespaces);

            return [
                'status' => 'success',
                'message' => 'Package Added Successfully successfully.',
                'namespaces' => $namespaces,
            ];
        } catch (\Exception $e) {
            Log::error('Error updating composer autoload: '.$e->getMessage());

            return [
                'status' => 'error',
                'message' => 'Failed to Add Package '.$e->getMessage(),
            ];
        }
    }

    private static function registerServiceProviders(array $namespaces)
    {
        $storedProviders = self::getStoredProviders();
        foreach ($namespaces as $namespace => $path) {
            $namespace = rtrim($namespace, '\\');
            $packageName = class_basename($namespace);
            $providerClass = "$namespace\\".$packageName.'ServiceProvider';
            $providerPath = base_path($path.$packageName.'ServiceProvider.php');

            if (File::exists($providerPath)) {
                require_once $providerPath;

                if (class_exists($providerClass) && ! in_array($providerClass, $storedProviders)) {
                    $storedProviders[] = $providerClass;

                }
            } else {

            }
        }

        // Save providers persistently
        self::storeProviders($storedProviders);
    }

    public static function getStoredProviders()
    {
        $path = storage_path('app/dynamic_providers.php');

        return file_exists($path) ? include $path : [];
    }

    private static function storeProviders(array $providers)
    {
        $path = storage_path('app/dynamic_providers.php');
        file_put_contents($path, "<?php\n return ".var_export($providers, true).';');
    }

    private static function updateComposerJson(array $namespaces)
    {
        try {
            $composerFile = base_path('composer.json');

            if (! file_exists($composerFile)) {
                throw new \Exception('composer.json not found.');
            }

            $composerJson = json_decode(file_get_contents($composerFile), true);

            if (! isset($composerJson['autoload']['psr-4'])) {
                $composerJson['autoload']['psr-4'] = [];
            }

            foreach ($namespaces as $namespace => $path) {
                $composerJson['autoload']['psr-4'][$namespace] = $path;
            }

            file_put_contents($composerFile, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            return ['status' => 'success'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    private static function updateAutoloadPsr4(array $namespaces)
    {
        try {
            $autoloadFile = base_path('vendor/composer/autoload_psr4.php');

            if (! file_exists($autoloadFile)) {
                $errorMessage = 'Autoload PSR-4 file not found: '.$autoloadFile;
                Log::error($errorMessage);

                return ['status' => 'error', 'message' => $errorMessage];
            }

            // Load the existing autoload array
            $autoloadArray = include $autoloadFile;

            // Ensure it's a valid array
            if (! is_array($autoloadArray)) {
                $errorMessage = 'Invalid autoload_psr4.php format.';

                return ['status' => 'error', 'message' => $errorMessage];
            }

            // Merge new namespaces while preserving existing structure
            foreach ($namespaces as $namespace => $path) {
                // Ensure new paths use `$baseDir`
                $autoloadArray[$namespace] = ["\$baseDir . '/".trim($path, '/')."'"];
            }

            // Manually construct the PHP file to preserve $vendorDir and $baseDir
            $phpContent = <<<'PHP'
    <?php
    
    // autoload_psr4.php @generated by Composer
    
    $vendorDir = dirname(__DIR__);
    $baseDir = dirname($vendorDir);
    
    return array(
    PHP;

            // Process each namespace, preserving existing format
            foreach ($autoloadArray as $namespace => $paths) {
                $formattedPaths = [];
                foreach ($paths as $path) {
                    // Preserve $vendorDir and $baseDir for existing namespaces
                    if (strpos($path, 'vendor/') !== false) {
                        $formattedPaths[] = "\$vendorDir . '".str_replace(base_path('vendor'), '', $path)."'";
                    } elseif (strpos($path, base_path()) !== false) {
                        $formattedPaths[] = "\$baseDir . '".str_replace(base_path(), '', $path)."'";
                    } else {
                        $formattedPaths[] = "$path";
                    }
                }
                $escapedNamespace = addslashes($namespace);
                $phpContent .= "\n    '$escapedNamespace' => array(".implode(', ', $formattedPaths).'),';
            }

            $phpContent .= "\n);\n";

            // Write the corrected content back to autoload_psr4.php
            if (file_put_contents($autoloadFile, $phpContent) !== false) {

                return ['status' => 'success'];
            } else {
                $errorMessage = 'Failed to update autoload_psr4.php.';
                Log::error($errorMessage);

                return ['status' => 'error', 'message' => $errorMessage];
            }
        } catch (\Exception $e) {

            return ['status' => 'error', 'message' => 'Exception: '.$e->getMessage()];
        }
    }

    private static function updateAutoloadClassmap()
    {
        try {
            $autoloadClassmapFile = base_path('vendor/composer/autoload_classmap.php');

            if (! file_exists($autoloadClassmapFile)) {
                $errorMessage = 'Class map file does not exist.';

                return ['status' => 'error', 'message' => $errorMessage];
            }

            // Read the existing content
            $content = file_get_contents($autoloadClassmapFile);

            // Ensure file has `$vendorDir` and `$baseDir`
            if (strpos($content, '$vendorDir') === false || strpos($content, '$baseDir') === false) {
                $errorMessage = 'Class map missing `$vendorDir` and `$baseDir`.';

                return ['status' => 'error', 'message' => $errorMessage];
            }

            // Scan `packages/*/src/**` for PHP files
            $packageBasePath = base_path('packages');
            $packageDirectories = [];

            // Filter iterator to exclude vendor folders
            $directoryIterator = new RecursiveDirectoryIterator($packageBasePath, RecursiveDirectoryIterator::SKIP_DOTS);
            $filterIterator = new RecursiveCallbackFilterIterator($directoryIterator, function ($file, $key, $iterator) {
                return ! ($file->isDir() && $file->getFilename() === 'vendor');
            });

            $iterator = new RecursiveIteratorIterator($filterIterator, RecursiveIteratorIterator::SELF_FIRST);

            foreach ($iterator as $file) {
                if ($file->isDir() && $file->getFilename() === 'src') {
                    $packageDirectories[] = $file->getPathname();
                }
            }

            $files = [];
            foreach ($packageDirectories as $srcDir) {
                $iterator = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($srcDir, RecursiveDirectoryIterator::SKIP_DOTS)
                );

                foreach ($iterator as $file) {
                    if ($file->isFile() && $file->getExtension() === 'php') {
                        $files[] = $file->getPathname();
                    }
                }
            }

            $newEntries = '';

            foreach ($files as $file) {
                $relativePath = str_replace(base_path().DIRECTORY_SEPARATOR, '', $file);
                $relativePath = str_replace('\\', '/', $relativePath); // Normalize Windows paths
                $className = self::getClassNameFromFile($file);

                if ($className) {
                    // Correctly escape class name
                    $escapedClass = str_replace('\\', '\\\\', $className);
                    $searchClass = str_replace('\\\\', '\\', $escapedClass);

                    // Check if class already exists
                    if (strpos($content, "'{$searchClass}' =>") === false) {
                        $newEntries .= "    '$escapedClass' => \$baseDir . '/$relativePath',\n";
                    }
                }
            }

            // Ensure we insert new entries correctly inside `return array()`
            $pattern = '/return array\s*\(/';

            if (! empty($newEntries) && preg_match($pattern, $content)) {
                $updatedContent = preg_replace($pattern, "return array (\n$newEntries", $content);

                if (file_put_contents($autoloadClassmapFile, $updatedContent) !== false) {
                    return ['status' => 'success'];
                } else {
                    $errorMessage = 'Failed to update autoload classmap.';

                    return ['status' => 'error', 'message' => $errorMessage];
                }
            } else {
                return ['status' => 'success', 'message' => 'No new classes found to add.'];
            }
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Exception: '.$e->getMessage()];
        }
    }

    private static function getClassNameFromFile($filePath)
    {
        $contents = file_get_contents($filePath);

        $namespace = '';
        $className = '';

        // Extract namespace
        if (preg_match('/namespace\s+([^\s;]+);/i', $contents, $namespaceMatch)) {
            $namespace = trim($namespaceMatch[1]).'\\';
        }

        // Extract class, trait, or interface name
        if (preg_match('/\b(class|trait|interface)\s+(\w+)/i', $contents, $classMatch)) {
            $className = trim($classMatch[2]);
        }

        if ($className) {
            return str_replace('\\', '\\\\', $namespace.$className);
        }

        return null;
    }

    private static function updateAutoloadStatic(array $namespaces)
    {
        try {
            $autoloadStaticFile = base_path('vendor/composer/autoload_static.php');

            if (! file_exists($autoloadStaticFile)) {
                $errorMessage = 'autoload_static.php file does not exist.';
                Log::error($errorMessage);

                return ['status' => 'error', 'message' => $errorMessage];
            }

            $content = file_get_contents($autoloadStaticFile);

            if (strpos($content, 'public static $prefixDirsPsr4') === false) {
                $errorMessage = 'Failed to locate $prefixDirsPsr4 in autoload_static.php.';
                Log::error($errorMessage);

                return ['status' => 'error', 'message' => $errorMessage];
            }

            // Generate new entries for the PSR-4 autoload array
            $newEntries = '';
            foreach ($namespaces as $namespace => $path) {
                // Ensure double backslashes are correctly written
                $escapedNamespace = str_replace('\\', '\\\\\\\\', $namespace); // Now forcing four backslashes!
                $escapedPath = addslashes($path);

                $searchClass = str_replace('\\\\\\\\', '\\\\', $escapedNamespace);

                // Only add if not already in the file
                if (strpos($content, "'{$searchClass}' =>") === false) {
                    $newEntries .= "        '{$escapedNamespace}' => array(\n            __DIR__ . '/../..' . '/{$escapedPath}',\n        ),\n";
                }
            }

            $pattern = '/(public static \\$prefixDirsPsr4 = array \\()/';
            if (! empty($newEntries) && preg_match($pattern, $content)) {
                $content = preg_replace($pattern, "$1\n$newEntries", $content);

                if (file_put_contents($autoloadStaticFile, $content) !== false) {

                    return ['status' => 'success', 'message' => 'autoload_static.php updated successfully.'];
                } else {
                    $errorMessage = 'Failed to update autoload_static.php.';

                    return ['status' => 'error', 'message' => $errorMessage];
                }
            } else {
                $infoMessage = 'No new namespaces to add or $prefixDirsPsr4 not found.';

                return ['status' => 'success', 'message' => $infoMessage];
            }
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Exception: '.$e->getMessage()];
        }

    }
}
