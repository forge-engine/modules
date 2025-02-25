<?php

namespace Forge\Modules\PackageManager\Src\Services;

use Forge\Core\Helpers\App;
use Forge\Core\Traits\OutputHelper;

class PackageManager
{
    use OutputHelper;

    private array $registry;
    private string $modulesPath;
    private DependencyResolver $resolver;
    private GitDownloader $downloader;

    public function __construct()
    {
        $config = App::config();
        $registryConfiguration = $config->get('package_manager.registry', []);
        $cachettl = $config->get('package_manager.cache_ttl', 3600);
        $this->registry = $registryConfiguration;
        $this->modulesPath = BASE_PATH . '/modules/';
        $this->resolver = new DependencyResolver();
        $this->downloader = new GitDownloader($cachettl);
    }

    public function install(string $moduleName): void
    {

        $this->validateModuleName($moduleName);

        if ($this->isInstalled($moduleName)) {
            throw new \RuntimeException("Module {$moduleName} is already installed");
        }

        $module = $this->findModule($moduleName);

        $this->resolver->resolve($module['manifest']['requires']);

        $this->installModule($module);
    }

    private function findModule(string $moduleName): array
    {

        foreach ($this->registry as $repoUrl) {

            $modules = $this->downloader->getRepoModules($repoUrl);
            if (isset($modules[$moduleName])) {
                return $modules[$moduleName];
            }
        }

        throw new \RuntimeException("Module $moduleName not found in registry");
    }

    private function installModule(array $module): void
    {
        $targetPath = $this->modulesPath . basename($module['path']);

        if (!mkdir($targetPath, 0755, true) && !is_dir($targetPath)) {
            throw new \RuntimeException("Failed to create directory: $targetPath");
        }

        $this->copyDirectory($module['path'], $targetPath);
    }

    private function copyDirectory(string $source, string $dest): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $target = $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
            $item->isDir() ? mkdir($target) : copy($item, $target);
        }
    }

    private function getInstalledModules(): array
    {
        $modules = [];
        $dirIterator = new \DirectoryIterator($this->modulesPath);

        foreach ($dirIterator as $fileInfo) {
            if ($fileInfo->isDir() && !$fileInfo->isDot()) {
                $manifestPath = $fileInfo->getPathname() . '/forge.json';
                if (file_exists($manifestPath)) {
                    $manifest = json_decode(file_get_contents($manifestPath), true);
                    if (isset($manifest['name'])) {
                        $modules[$manifest['name']] = $fileInfo->getPathname();
                    }
                }
            }
        }

        return $modules;
    }

    private function isInstalled(string $moduleName): bool
    {
        $installed = $this->getInstalledModules();
        return isset($installed[$moduleName]);
    }

    private function validateModuleName(string $name): void
    {
        if (!preg_match('/^[a-z0-9-]+$/', $name)) {
            throw new \InvalidArgumentException("Invalid module name format");
        }
    }


}