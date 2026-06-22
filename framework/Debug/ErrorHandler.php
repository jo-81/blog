<?php

declare(strict_types=1);

namespace Framework\Debug;

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

/**
 * Gestionnaire d'erreurs interne au framework. Elle encapsule Whoops.
 */
class ErrorHandler
{
    public static function register(bool $isDebug): void
    {
        if (!$isDebug) {
            return;
        }

        $whoops = new Run();
        $whoops->pushHandler(new PrettyPageHandler());
        $whoops->register();
    }
}
