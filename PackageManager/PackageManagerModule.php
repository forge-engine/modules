<?php

namespace Forge\Modules\PackageManager;


use Forge\Core\Contracts\Command\CommandInterface;
use Forge\Core\Contracts\Modules\ModulesInterface;
use Forge\Core\DependencyInjection\Container;
use Forge\Core\Helpers\Debug;
use Forge\Modules\PackageManager\Src\Commands\InstallModuleCommand;
use Forge\Modules\PackageManager\Src\Services\PackageManager;
use Forge\Modules\PackageManager\Src\Contracts\PackageManagerInterface;

class PackageManagerModule extends ModulesInterface
{
    public function register(Container $container): void
    {
        Debug::addEvent("[PackageManagerModule] Registration", "start");

        if (PHP_SAPI === 'cli') {
            $module = new PackageManager();
            $container->instance(PackageManagerInterface::class, $module);

            $container->bind(CommandInterface::class, InstallModuleCommand::class);
            $container->tag(InstallModuleCommand::class, ['module.command']);
        }

        Debug::addEvent("[PackageManagerModule] Registration", "end");
    }
}