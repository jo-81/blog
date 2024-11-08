<?php

use function DI\get;
use function DI\create;
use function DI\factory;

use Blog\Core\Router\Router;
use Blog\Core\FrontController;
use Psr\Container\ContainerInterface;
use Blog\Core\Middleware\RouterMiddleware;
use Blog\Core\Middleware\MiddlewareHandler;
use Blog\Core\Renderer\TwigRendererFactory;
use Blog\Core\Middleware\NotFoundMiddleware;
use Blog\Core\Middleware\ResponseMiddleware;
use Blog\Core\Middleware\TrailingSlashMiddleware;
use Blog\Core\Renderer\Extension\FormViewTwigExtension;

return [
    "app.middlewares" => [
        create(TrailingSlashMiddleware::class),
        create(RouterMiddleware::class)->constructor(get("app.router")),
        create(ResponseMiddleware::class)->constructor(get(ContainerInterface::class)),
        create(NotFoundMiddleware::class),
    ],

    "app.renderer_extension" => [
        create(FormViewTwigExtension::class),
    ],

    "app.routes_folder" => dirname(__DIR__) . "/src/Controller",

    "app.middleware_handler" => create(MiddlewareHandler::class)->constructor(get("app.middlewares")),
    "app.front_controller" => create(FrontController::class)->constructor(get("app.middleware_handler")),

    "app.router" => create(Router::class)->method('setRoutes', get("app.routes_folder")),

    "app.templates_directory" => dirname(__DIR__) . "/src/View",

    "app.renderer_interface" => factory(TwigRendererFactory::class),
];