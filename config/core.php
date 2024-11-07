<?php

use function DI\get;
use function DI\create;

use Blog\Core\FrontController;
use Blog\Core\Middleware\MiddlewareHandler;
use Blog\Core\Middleware\NotFoundMiddleware;
use Blog\Core\Middleware\TrailingSlashMiddleware;

return [
    "app.middlewares" => [
        create(TrailingSlashMiddleware::class),
        create(NotFoundMiddleware::class),
    ],

    "app.middleware_handler" => create(MiddlewareHandler::class)->constructor(get("app.middlewares")),
    "app.front_controller" => create(FrontController::class)->constructor(get("app.middleware_handler")),
];