<?php

namespace Forge\Modules\ViewEngine\Contracts;

interface DirectiveInterface
{
    public function handle(string $content): string;
}
