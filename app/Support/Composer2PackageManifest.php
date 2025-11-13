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

        if (isset($installed[0])) {
            return $this->mapPackageNames($installed);
        }

        return [];
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

            return isset($package['name']) ? $package : null;
        }, $packages)));
    }
}
