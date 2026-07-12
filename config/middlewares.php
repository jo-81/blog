<?php

use Framework\Http\Middlewares\CsrfMiddleware;
use Framework\Http\Middlewares\RequestHandlerMiddleware;
use Framework\Http\Middlewares\RoutingMiddleware;
use Framework\Http\Middlewares\SessionMiddleware;
use Middlewares\AccessLog;
use Middlewares\Debugbar;
use Middlewares\ErrorHandler;
use Middlewares\TrailingSlash;
use Middlewares\Whoops;
use Psr\Http\Message\ResponseFactoryInterface;
use Framework\Http\Middlewares\AuthentificationMiddleware;

$environment = $_ENV['APP_ENV'] ?? 'prod';

$coreMiddlewares = [
    TrailingSlash::class,
    RoutingMiddleware::class,
    SessionMiddleware::class,
    AuthentificationMiddleware::class,
    CsrfMiddleware::class,
    RequestHandlerMiddleware::class,
];

$middlewares = match ($environment) {
    'dev'     => [Whoops::class, Debugbar::class, AccessLog::class, ...$coreMiddlewares],
    'prod'    => [ErrorHandler::class, AccessLog::class, ...$coreMiddlewares],
    'testing' => $coreMiddlewares,
    default   => $coreMiddlewares,
};

return [
    'app.middlewares' => $middlewares,

    TrailingSlash::class => DI\autowire()->method('redirect', DI\get(ResponseFactoryInterface::class)),
];