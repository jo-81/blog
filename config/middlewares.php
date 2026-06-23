<?php

use Middlewares\Whoops;
use Middlewares\Debugbar;
use Middlewares\AccessLog;
use Middlewares\TrailingSlash;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Framework\Http\Middlewares\RoutingMiddleware;
use Framework\Http\Middlewares\ErrorHandlingMiddleware;
use Framework\Http\Middlewares\RequestHandlerMiddleware;

return [
    'app.middlewares' => [
        Whoops::class,
        ErrorHandlingMiddleware::class,
        Debugbar::class,
        AccessLog::class,
        TrailingSlash::class,
        RoutingMiddleware::class,
        RequestHandlerMiddleware::class,
    ],

    ErrorHandlingMiddleware::class => fn(ContainerInterface $c) => new ErrorHandlingMiddleware(
        $c->get(ResponseFactoryInterface::class),
        $c->get('settings.debug')
    ),

    TrailingSlash::class => DI\autowire()->method('redirect', DI\get(ResponseFactoryInterface::class)),
];