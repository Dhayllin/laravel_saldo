<?php

namespace App\Support;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\PackageManifest;

class Composer2PackageManifest extends PackageManifest
{
    /**
     * Create a new package manifest instance.
     */
    public function __construct(Filesystem $files, $basePath, $manifestPath)
    {
        parent::__construct($files, $basePath, $manifestPath);
    }

    /**
     * Retrieve the list of installed Composer packages.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function getInstalledPackages()
    {
        $packages = $this->readInstalledJson();

        if (! empty($packages)) {
            return $packages;
        }

        return $this->readInstalledPhp();
    }

    /**
     * Retrieve the packages keyed by their name as Laravel expects.
     *
     * Laravel 5.7's base implementation assumes the Composer metadata
     * already contains a valid "name" attribute. Composer 2 stores the
     * metadata in a different structure which omits that key in some
     * contexts, so we rebuild the array using the sanitized package list.
     *
     * @return array<string, array<string, mixed>>
     */
    protected function getPackages()
    {
        if ($this->packages !== null) {
            return $this->packages;
        }

        $packages = [];

        foreach ($this->getInstalledPackages() as $package) {
            if (! is_array($package)) {
                continue;
            }

            $name = $package['name'] ?? null;

            if (! is_string($name) || $name === '') {
                continue;
            }

            $packages[$name] = $package;
        }

        return $this->packages = $packages;
    }

    /**
     * Normalize package information from Composer's installed.json file.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function readInstalledJson()
    {
        $path = $this->vendorPath.'/composer/installed.json';

        if (! $this->files->exists($path)) {
            return [];
        }

        $installed = json_decode($this->files->get($path), true);

        if (! is_array($installed) || empty($installed)) {
            return [];
        }

        if (isset($installed['packages'])) {
            $packages = $installed['packages'];

            if (isset($installed['packages-dev'])) {
                $packages = array_merge($packages, $installed['packages-dev']);
            }

            return $this->mapPackageNames($packages);
        }

        if ($this->isSequentialArray($installed)) {
            $packages = [];

            foreach ($installed as $section) {
                if (! is_array($section)) {
                    continue;
                }

                if (isset($section['packages']) && is_array($section['packages'])) {
                    $packages = array_merge($packages, $section['packages']);
                } elseif (isset($section['name'])) {
                    $packages[] = $section;
                }

                if (isset($section['packages-dev']) && is_array($section['packages-dev'])) {
                    $packages = array_merge($packages, $section['packages-dev']);
                }
            }

            return $this->mapPackageNames($packages);
        }

        return $this->mapPackageNames((array) $installed);
    }

    /**
     * Normalize package information from Composer's installed.php file.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function readInstalledPhp()
    {
        $path = $this->vendorPath.'/composer/installed.php';

        if (! $this->files->exists($path)) {
            return [];
        }

        $installed = require $path;

        if (! is_array($installed)) {
            return [];
        }

        if (isset($installed['versions']) && is_array($installed['versions'])) {
            $packages = [];

            foreach ($installed['versions'] as $name => $package) {
                if (! is_array($package)) {
                    $package = [];
                }

                $package['name'] = $name;
                $packages[] = $package;
            }

            return $packages;
        }

        if (isset($installed[0]) && isset($installed[0]['name'])) {
            return $installed;
        }

        return [];
    }

    /**
     * Ensure every package array has a name attribute.
     *
     * @param  array<int, array<string, mixed>>  $packages
     * @return array<int, array<string, mixed>>
     */
    protected function mapPackageNames(array $packages)
    {
        return array_values(array_filter(array_map(function ($package) {
            if (! is_array($package)) {
                return null;
            }

            if (! isset($package['name']) && isset($package['package'])) {
                $package['name'] = $package['package'];
            }

            if (! isset($package['name']) && isset($package['pretty_name'])) {
                $package['name'] = $package['pretty_name'];
            }

            if (isset($package['extra']['laravel']['providers'])) {
                $package['extra']['laravel']['providers'] = $this->normalizeProviders(
                    $package['extra']['laravel']['providers']
                );
            }

            if (isset($package['extra']['laravel']['aliases']) && ! is_array($package['extra']['laravel']['aliases'])) {
                $package['extra']['laravel']['aliases'] = [];
            }

            return isset($package['name']) ? $package : null;
        }, $packages)));
    }

    /**
     * Determine if the given array is sequential.
     */
    protected function isSequentialArray($array)
    {
        if (! is_array($array)) {
            return false;
        }

        return array_keys($array) === range(0, count($array) - 1);
    }

    /**
     * Normalize provider declarations into an array of class strings.
     *
     * @param  mixed  $providers
     * @return array<int, string>
     */
    protected function normalizeProviders($providers)
    {
        if (is_string($providers)) {
            return [$providers];
        }

        if (! is_array($providers)) {
            return [];
        }

        return array_values(array_filter($providers, function ($provider) {
            return is_string($provider) && $provider !== '';
        }));
    }
}
