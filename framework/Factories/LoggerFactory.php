<?php

declare(strict_types=1);

namespace Framework\Factories;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Monolog\Handler\StreamHandler;
use Psr\Container\ContainerInterface;

class LoggerFactory
{
    public function __invoke(ContainerInterface $container): LoggerInterface
    {
        $logger = new Logger('app');

        $logPath = __DIR__ . '/../../var/logs/access.log';
        $logger->pushHandler(new StreamHandler($logPath, \Monolog\Level::Debug));

        return $logger;
    }
}
