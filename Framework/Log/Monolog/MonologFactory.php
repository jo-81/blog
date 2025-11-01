<?php

declare(strict_types=1);

namespace Framework\Log\Monolog;

use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Monolog\Handler\StreamHandler;
use Psr\Container\ContainerInterface;

final class MonologFactory
{
    public function __invoke(ContainerInterface $container): LoggerInterface
    {
        $logger = new Logger('app');
        if ($container->has('app.file_log')) {
            $logger->pushHandler(new StreamHandler($container->get('app.file_log') . "/debug.log", Level::Debug));
        }


        return $logger;
    }
}
