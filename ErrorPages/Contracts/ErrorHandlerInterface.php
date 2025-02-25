<?php

namespace Forge\Modules\ErrorPages\Contracts;

use Forge\Http\Request;
use Forge\Http\Response;
use Throwable;

interface ErrorHandlerInterface
{
    public function handle(Throwable $e, Request $request): Response;
}
