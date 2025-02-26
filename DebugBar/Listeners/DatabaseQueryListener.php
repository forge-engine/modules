<?php

namespace Forge\Modules\DebugBar\Listeners;

use Forge\Core\Contracts\Events\EventInterface;
use Forge\Core\Contracts\Events\ListenerInterface;
use Forge\Core\Contracts\Modules\DebugBarInterface;
use Forge\Core\Helpers\App;
use Forge\Modules\DebugBar\Collectors\DatabaseCollector;
use Forge\Core\Events\DatabaseQueryExecuted;

class DatabaseQueryListener implements ListenerInterface
{
    private DebugBarInterface $debugBar;

    public function __construct(DebugBarInterface $debugBar)
    {
        $this->debugBar = $debugBar;
    }

    public function handle(EventInterface $event): void
    {
        if ($event instanceof DatabaseQueryExecuted) {
            /** @var DatabaseCollector $databaseCollectorInstance */
            $databaseCollectorInstance = App::getContainer()->get(DatabaseCollector::class);
            $databaseCollectorInstance::instance()->addQuery(
                $event->query,
                $event->bindings,
                $event->timeInMilliseconds,
                $event->connectionName,
                $event->origin
            );
        }
    }
}