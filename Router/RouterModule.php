<?php

namespace Forge\Modules\Router;

use Forge\Core\Contracts\Modules\ModulesInterface;
use Forge\Core\Contracts\Modules\RouterInterface;
use Forge\Core\DependencyInjection\Container;
use Forge\Core\Module\ModuleManifest;
use Forge\Core\Configuration\Config;

class RouterModule extends ModulesInterface
{
    private ModuleManifest $manifest;

    public function __construct(ModuleManifest $manifest)
    {
        $this->manifest = $manifest;
    }

    public function register(Container $container): void
    {
        $container->instance(RouterInterface::class, new BasicRouter($container));
        $container->instance(ModuleManifest::class, $this->manifest);
    }

    public function onAfterConfigLoaded(Container $container): void
    {
        $config = $container->get(Config::class);
        $get = $config->get('methods.get');

        //echo "Config value 'methods.get': " . $get . "\n";
    }
}
