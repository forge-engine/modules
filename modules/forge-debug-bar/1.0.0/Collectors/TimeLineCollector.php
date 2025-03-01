<?php

namespace Forge\Modules\ForgeDebugbar\Collectors;

use Forge\Core\Helpers\Debug;

class TimelineCollector implements CollectorInterface
{
    private array $events = [];

    public static function collect(...$args): array
    {
        return self::instance()->events;
    }

    public static function instance(): self
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new self();
        }
        return $instance;
    }

    public function addEvent(string $name, string $label = 'event', array $data = []): void
    {
        $this->events[] = [
            'name' => $name,
            'label' => $label,
            'time' => microtime(true),
            'data' => $data,
            'origin' => Debug::backtraceOrigin()
        ];
    }
}