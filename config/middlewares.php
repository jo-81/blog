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

return [
    'app.middlewares' => [
        Whoops::class,
        // ErrorHandler::class,
        Debugbar::class,
        AccessLog::class,
        TrailingSlash::class,
        RoutingMiddleware::class,
        SessionMiddleware::class,
        CsrfMiddleware::class,
        RequestHandlerMiddleware::class,
    ],

    TrailingSlash::class => DI\autowire()->method('redirect', DI\get(ResponseFactoryInterface::class)),
];