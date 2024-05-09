<?php

use Framework\Middleware\MethodMiddleware;
use Framework\Middleware\MiddlewareHandler;
use Framework\Middleware\ResponseMiddleware;
use Framework\Middleware\RouterMiddleware;
use Framework\Middleware\TrailingSlashMiddleware;
use Psr\Container\ContainerInterface;

return [
    'app.middleware_handle' => DI\create(MiddlewareHandler::class)
            ->method('add', DI\get('app.middleware_trailing_slash'))
            ->method('add', DI\get('app.middleware_router'))
            ->method('add', DI\get('app.middleware_method'))
            ->method('add', DI\get('app.middleware_response')) /* last middleware ! */
    ,

    'app.middleware_trailing_slash' => DI\create(TrailingSlashMiddleware::class),
    'app.middleware_router' => DI\create(RouterMiddleware::class)->constructor(DI\get('app.router_interface')),
    'app.middleware_response' => DI\create(ResponseMiddleware::class)->constructor(DI\get(ContainerInterface::class)),
    'app.middleware_method' => DI\create(MethodMiddleware::class),
];
