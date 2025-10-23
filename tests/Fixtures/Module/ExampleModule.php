<?php

declare(strict_types=1);

namespace Tests\Fixtures\Module;

use Framework\Module\AbstractModule;

class ExampleModule extends AbstractModule
{
    public const DEFINITION = __DIR__ . "/config.php";
}
