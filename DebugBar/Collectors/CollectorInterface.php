<?php

namespace Forge\Modules\DebugBar\Collectors;

interface CollectorInterface
{
    public static function collect(...$args): mixed;
}