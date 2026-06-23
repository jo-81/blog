<?php

use Middlewares\TrailingSlash;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Framework\Http\Middlewares\WhoopsMiddleware;
use Framework\Http\Middlewares\RoutingMiddleware;
use Framework\Http\Middlewares\ErrorHandlingMiddleware;
use Framework\Http\Middlewares\RequestHandlerMiddleware;

return [
    'app.middlewares' => [
        WhoopsMiddleware::class,
        ErrorHandlingMiddleware::class,
        TrailingSlash::class,
        RoutingMiddleware::class,
        RequestHandlerMiddleware::class,
    ],

    ErrorHandlingMiddleware::class => fn(ContainerInterface $c) => new ErrorHandlingMiddleware(
        $c->get(ResponseFactoryInterface::class),
        $c->get('settings.debug')
    ),

    WhoopsMiddleware::class => fn(ContainerInterface $c) => new WhoopsMiddleware(
        $c->get(ResponseFactoryInterface::class),
        $c->get('settings.debug')
    ),

    TrailingSlash::class => DI\autowire()->method('redirect', DI\get(ResponseFactoryInterface::class)),
];