<?php

use Framework\Http\Middlewares\CsrfMiddleware;
use Middlewares\Whoops;
use Middlewares\Debugbar;
use Middlewares\AccessLog;
use Middlewares\ErrorHandler;
use Middlewares\TrailingSlash;
use Psr\Http\Message\ResponseFactoryInterface;
use Framework\Http\Middlewares\RoutingMiddleware;
use Framework\Http\Middlewares\RequestHandlerMiddleware;
use Framework\Http\Middlewares\SessionMiddleware;

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