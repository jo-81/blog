<?php

use Framework\Middleware\MiddlewareHandler;
use Framework\Middleware\ResponseMiddleware;
use Framework\Middleware\RouterMiddleware;
use Framework\Middleware\TrailingSlashMiddleware;

return [

    "app.middleware_handle" 
        => DI\create(MiddlewareHandler::class)
            ->method("add", DI\get("app.middleware_trailing_slash"))
            ->method("add", DI\get("app.middleware_router"))
            ->method("add", DI\get("app.middleware_response"))
    ,

    "app.middleware_trailing_slash" => DI\create(TrailingSlashMiddleware::class),
    "app.middleware_router" => DI\create(RouterMiddleware::class)->constructor(DI\get("app.router_interface")),
    "app.middleware_response" => DI\create(ResponseMiddleware::class),
];