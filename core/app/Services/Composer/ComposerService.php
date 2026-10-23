<?php

namespace App\Services\Composer;

use Composer\InstalledVersions;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

class ComposerService {

    protected Filesystem $files;
    protected ?array $commandPrefix = null;

    public function __construct(Filesystem $files) {
        $this->files = $files;
    }

    /**
     * Dynamically discover the Composer command prefix cross-platform
     * Works seamlessly across Linux, macOS, Windows, Docker, and shared environments
     */
    public function getCommandPrefix(): array {
        if ($this->commandPrefix !== null) {
            return $this->commandPrefix;
        }

        // 1. Check environment variable override (e.g. COMPOSER_BINARY=/usr/local/bin/composer)
        $envBinary = env('COMPOSER_BINARY') ?: env('COMPOSER_PATH');
        if ($envBinary && $this->files->exists($envBinary)) {
            return $this->commandPrefix = [$envBinary];
        }

        // 2. Discover dynamically using Symfony ExecutableFinder on system PATH
        $finder = new ExecutableFinder();
        $binary = $finder->find('composer') 
            ?: $finder->find('composer.bat') 
            ?: $finder->find('composer.phar');

        if ($binary) {
            if (str_ends_with(strtolower($binary), '.phar')) {
                $php = (new ExecutableFinder())->find('php') ?: PHP_BINARY;
                return $this->commandPrefix = [$php, $binary];
            }
            return $this->commandPrefix = [$binary];
        }

        // 3. Fallback: check for local composer.phar in project root
        $localPhar = base_path('composer.phar');
        if ($this->files->exists($localPhar)) {
            $php = (new ExecutableFinder())->find('php') ?: PHP_BINARY;
            return $this->commandPrefix = [$php, $localPhar];
        }

        return $this->commandPrefix = [];
    }

    /**
     * Check if a composer package is currently installed
     */
    public function isInstalled(string $package): bool {
        return class_exists(InstalledVersions::class) && InstalledVersions::isInstalled($package);
    }

    /**
     * Get installed package version
     */
    public function getVersion(string $package): ?string {
        if ($this->isInstalled($package)) {
            return InstalledVersions::getPrettyVersion($package);
        }
        return null;
    }

    /**
     * Remove one or more composer packages and clean dependencies from codebase safely
     */
    public function remove(array|string $packages, bool $dumpAutoload = true): bool {
        $packages = is_array($packages) ? $packages : [$packages];
        $packages = array_values(array_filter(array_map('trim', $packages)));

        if (empty($packages)) {
            return true;
        }

        // Try Composer CLI removal first if available
        $prefix = $this->getCommandPrefix();
        if (!empty($prefix)) {
            $args = array_merge(['remove', '--no-interaction', '--no-scripts'], $packages);
            if ($this->run($args, timeout: 60)) {
                $this->clearLaravelBootstrapManifest();
                try {
                    Artisan::call('package:discover');
                } catch (\Throwable $e) {}
                return true;
            }
        }

        // Fallback: Safe and comprehensive manual removal
        return $this->removeManually($packages, $dumpAutoload);
    }

    /**
     * Comprehensive manual package removal that purges all composer metadata
     * (composer.json, installed.json, installed.php, autoload mappings, and bootstrap cache)
     */
    public function removeManually(array $packages, bool $dumpAutoload = true): bool {
        try {
            $composerJsonPath = base_path('composer.json');
            if ($this->files->exists($composerJsonPath)) {
                $manifest = json_decode($this->files->get($composerJsonPath), true);
                if (is_array($manifest)) {
                    $modified = false;
                    foreach ($packages as $pkg) {
                        if (isset($manifest['require'][$pkg])) {
                            unset($manifest['require'][$pkg]);
                            $modified = true;
                        }
                        if (isset($manifest['require-dev'][$pkg])) {
                            unset($manifest['require-dev'][$pkg]);
                            $modified = true;
                        }
                    }
                    if ($modified) {
                        $this->files->put($composerJsonPath, json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                    }
                }
            }

            // Purge from vendor/composer/installed.json
            $installedJsonPath = base_path('vendor/composer/installed.json');
            if ($this->files->exists($installedJsonPath)) {
                $installedData = json_decode($this->files->get($installedJsonPath), true);
                if (is_array($installedData)) {
                    $changed = false;
                    if (isset($installedData['packages']) && is_array($installedData['packages'])) {
                        $installedData['packages'] = array_values(array_filter($installedData['packages'], function ($item) use ($packages, &$changed) {
                            $name = strtolower($item['name'] ?? '');
                            foreach ($packages as $pkg) {
                                if (strtolower($pkg) === $name) {
                                    $changed = true;
                                    return false;
                                }
                            }
                            return true;
                        }));
                    } else {
                        // Flat array format
                        $installedData = array_values(array_filter($installedData, function ($item) use ($packages, &$changed) {
                            $name = strtolower($item['name'] ?? '');
                            foreach ($packages as $pkg) {
                                if (strtolower($pkg) === $name) {
                                    $changed = true;
                                    return false;
                                }
                            }
                            return true;
                        }));
                    }
                    if ($changed) {
                        $this->files->put($installedJsonPath, json_encode($installedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                    }
                }
            }

            // Purge from vendor/composer/installed.php
            $installedPhpPath = base_path('vendor/composer/installed.php');
            if ($this->files->exists($installedPhpPath)) {
                try {
                    $installedPhp = include $installedPhpPath;
                    if (is_array($installedPhp) && isset($installedPhp['versions'])) {
                        $changed = false;
                        foreach ($packages as $pkg) {
                            if (isset($installedPhp['versions'][$pkg])) {
                                unset($installedPhp['versions'][$pkg]);
                                $changed = true;
                            }
                        }
                        if ($changed) {
                            $export = var_export($installedPhp, true);
                            $this->files->put($installedPhpPath, "<?php return {$export};\n");
                        }
                    }
                } catch (\Throwable $e) {
                    Log::warning("ComposerService: Error updating installed.php: " . $e->getMessage());
                }
            }

            // Purge PSR-4 mappings in vendor/composer/autoload_psr4.php for these packages
            $autoloadPsr4Path = base_path('vendor/composer/autoload_psr4.php');
            if ($this->files->exists($autoloadPsr4Path)) {
                $lines = file($autoloadPsr4Path);
                $newLines = [];
                foreach ($lines as $line) {
                    $matchesPackage = false;
                    foreach ($packages as $pkg) {
                        if (stripos($line, '/' . $pkg . '/') !== false || stripos($line, '/' . $pkg . '\'') !== false) {
                            $matchesPackage = true;
                            break;
                        }
                    }
                    if (!$matchesPackage) {
                        $newLines[] = $line;
                    }
                }
                $this->files->put($autoloadPsr4Path, implode('', $newLines));
            }

            // Clear cached packages.php in bootstrap/cache
            $this->clearLaravelBootstrapManifest();

            // Finally remove the directory from vendor/
            foreach ($packages as $pkg) {
                $vendorDir = base_path("vendor/{$pkg}");
                if ($this->files->isDirectory($vendorDir)) {
                    $this->files->deleteDirectory($vendorDir);
                }

                // Remove empty parent namespace directory if empty (e.g. vendor/mollie)
                $namespaceDir = dirname($vendorDir);
                if ($this->files->isDirectory($namespaceDir) && count($this->files->files($namespaceDir)) === 0 && count($this->files->directories($namespaceDir)) === 0) {
                    $this->files->deleteDirectory($namespaceDir);
                }
            }

            // Re-discover remaining packages cleanly
            try {
                Artisan::call('package:discover');
            } catch (\Throwable $e) {}

            if ($dumpAutoload) {
                $this->dumpAutoload();
            }

            return true;
        } catch (\Throwable $e) {
            Log::error("ComposerService: Failed manually removing packages (" . implode(', ', $packages) . "): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Clear cached package discovery manifest in bootstrap/cache
     */
    public function clearLaravelBootstrapManifest(): void {
        $packagesCache = base_path('bootstrap/cache/packages.php');
        if ($this->files->exists($packagesCache)) {
            $this->files->delete($packagesCache);
        }
        $servicesCache = base_path('bootstrap/cache/services.php');
        if ($this->files->exists($servicesCache)) {
            $this->files->delete($servicesCache);
        }
    }

    /**
     * Re-generate Composer autoload classmaps
     */
    public function dumpAutoload(bool $optimize = true): bool {
        $args = ['dump-autoload', '--no-interaction', '--no-scripts'];
        if ($optimize) {
            $args[] = '-o';
        }
        return $this->run($args);
    }

    /**
     * Execute a composer process safely with Symfony Process
     */
    public function run(array $arguments, int $timeout = 60): bool {
        $prefix = $this->getCommandPrefix();
        if (empty($prefix)) {
            Log::info("ComposerService: Composer executable not present in system environment. Skipping CLI execution.");
            return false;
        }

        try {
            $command = array_merge($prefix, $arguments);
            $process = new Process($command, base_path(), null, null, $timeout);
            $process->run();

            if (!$process->isSuccessful()) {
                Log::warning("ComposerService command (" . implode(' ', $arguments) . ") returned non-zero exit code: " . $process->getErrorOutput());
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning("ComposerService execution note: " . $e->getMessage());
            return false;
        }
    }
}
