<?php

namespace Forge\Modules\PackageManager\Src\Commands;

use Forge\Core\Contracts\Command\CommandInterface;
use Forge\Core\Helpers\App;
use Forge\Core\Traits\OutputHelper;
use Forge\Modules\PackageManager\Src\Services\PackageManager;

class InstallModuleCommand implements CommandInterface
{
    use OutputHelper;

    public function getName(): string
    {
        return 'install:module';
    }

    public function getDescription(): string
    {
        return 'Install a module from the registry';
    }

    public function execute(array $args): int
    {
        if (empty($args)) {
            $this->error("Module name required.");
            return 1;
        }
        $container = App::getContainer();
        /** @var PackageManager $packageManaer */
        $packageManaer = $container->get(PackageManager::class);

        try {
            $packageManaer->install(reset($args));
            $this->success("Module installed successfully.");
            return 0;
        } catch (\Throwable $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}