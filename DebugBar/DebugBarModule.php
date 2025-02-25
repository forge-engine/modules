<?php

namespace Forge\Modules\DebugBar;

use Forge\Core\Contracts\Modules\DebugBarInterface;
use Forge\Core\Contracts\Modules\ModulesInterface;
use Forge\Core\Bootstrap\AppManager;
use Forge\Core\Configuration\Config;
use Forge\Core\DependencyInjection\Container;
use Forge\Http\Response;

class DebugBarModule extends ModulesInterface
{
    public function register(Container $container): void
    {
        /**
         * @var AppManager $appManager
         */
        $appManager = $container->get(AppManager::class);

        if (method_exists($this, 'onAfterResponse')) {
            $appManager->addHook('afterResponse', [$this, 'onAfterResponse']);
        }
    }

    public function onAfterModuleRegister(Container $container): void
    {

    }

    public function onAfterResponse(Container $container): void
    {
        if (!$this->isDebugEnabled($container)) {
            return;
        }

        /**
         * @var Response $response
         * @var DebugBarInterface $debugbar
         */
        $response = $container->get(Response::class);
        $debugbar = $container->get(DebugBarInterface::class);
        $content = $response->getContent() . $debugbar->render();
        $response->setContent($content);
//        if (strpos($response->getHeader('Content-Type'), 'text/html') !== false) {
//            $content = $response->getContent() . $debugbar->render();
//            $response->setContent($content);
//        }
    }

    private function isDebugEnabled(Container $container): bool
    {
        $appEnv = $_ENV['APP_ENV'] ?? 'production';
        $forgeDebug = filter_var($_ENV['FORGE_APP_DEBUG'] ?? false);
        /**
         * @var Config $config
         */
        $config = $container->get(Config::class);
        $configEnabled = $config->get('debugbar.enabled', true);
        return $configEnabled && $appEnv !== 'production' && $forgeDebug;
    }

}