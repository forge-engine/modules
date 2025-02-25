<?php

namespace Forge\Modules\PackageManager\Src;

use Forge\Modules\PackageManager\Src\Contracts\PackageManagerInterface;
use Forge\Core\Helpers\Debug;

class PackageManager implements PackageManagerInterface
{
    public function __construct()
    {

    }

    public function test(): void
    {
        // Module logic here
        Debug::weblog("[ForgePackageManagerModule] Called", "start"); // Example log
    }
}