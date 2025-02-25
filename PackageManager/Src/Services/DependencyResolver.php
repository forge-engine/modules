<?php

namespace Forge\Modules\PackageManager\Src\Services;

use Forge\Core\Configuration\Config;
use Forge\Core\Helpers\App;

class DependencyResolver
{
    /** @inject */
    private PackageManager $packageManager;


    public function resolve(array $requirements): void
    {
        foreach ($requirements as $requirement) {
            if (!$this->isRequirementMet($requirement)) {
                $this->installRequirement($requirement);
            }
        }
    }

    private function isRequirementMet(string $requirement): bool
    {
        [$interface, $version] = explode('@', $requirement, 2);

        foreach ($this->getInstalledModules() as $module) {
            foreach ($module['provides'] as $provided) {
                if (str_starts_with($provided, $interface . '@')) {
                    return $this->compareVersions($version, explode('@', $provided, 2)[1]);
                }
            }
        }

        return false;
    }

    private function installRequirement(string $requirement): void
    {
        $config = App::config();
        foreach ($config->get('package_manager.registry', []) as $repoUrl) {
            $modules = (new GitDownloader())->getRepoModules($repoUrl);
            foreach ($modules as $module) {
                if (in_array($requirement, $module['manifest']['provides'], true)) {
                    $this->packageManager->install($module['manifest']['name']);
                    return;
                }
            }
        }

        throw new \RuntimeException("Unsatisfied dependency: $requirement");
    }

    private function getInstalledModules(): array
    {
        $modules = [];
        $dirIterator = new \DirectoryIterator(BASE_PATH . '/modules/');

        foreach ($dirIterator as $fileInfo) {
            if ($fileInfo->isDir()) {
                $manifestPath = $fileInfo->getPathname() . '/forge.json';
                if (file_exists($manifestPath)) {
                    $modules[] = json_decode(file_get_contents($manifestPath), true);
                }
            }
        }

        return $modules;
    }

    private function compareVersions(string $required, string $installed): bool
    {
        return version_compare($installed, $required, '>=');
    }
}