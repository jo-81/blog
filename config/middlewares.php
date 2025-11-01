<?php

use function DI\autowire;
use function DI\get;
use function DI\create;

use Framework\Http\Interface\ResponseFactoryInterface;
use Framework\Middleware\MiddlewareHandler;
use Framework\Middleware\MiddlewareRegistry;
use Framework\Middleware\Middlewares\ErrorHandlerMiddleware;
use Framework\Middleware\Middlewares\LoggingMiddleware;
use Framework\Middleware\Middlewares\ResponseMiddleware;
use Framework\Middleware\Middlewares\RoutingMiddleware;
use Framework\Middleware\Middlewares\TrailingSlashMiddleware;
use Framework\Middleware\Middlewares\RouteCallbackValidatorMiddleware;
use Framework\Middleware\Middlewares\SecurityHeadersMiddleware;
use Framework\Renderer\Interface\RendererInterface;
use Psr\Log\LoggerInterface;

return [
    MiddlewareRegistry::class => create(MiddlewareRegistry::class)
        ->method('registerMiddleware', get(ErrorHandlerMiddleware::class))
        ->method('registerMiddleware', get(LoggingMiddleware::class))
        ->method('registerMiddleware', get(SecurityHeadersMiddleware::class))
        ->method('registerMiddleware', get(TrailingSlashMiddleware::class))
        ->method('registerMiddleware', get(RoutingMiddleware::class))
        ->method('registerMiddleware', get(RouteCallbackValidatorMiddleware::class))
    ,

    MiddlewareHandler::class => autowire()->method('setFinalHandler', get(ResponseMiddleware::class)),

    TrailingSlashMiddleware::class => autowire(),
    RoutingMiddleware::class => autowire(),
    RouteCallbackValidatorMiddleware::class => autowire(),
    ResponseMiddleware::class => autowire(),
    ErrorHandlerMiddleware::class => create()
        ->constructor( 
            get(RendererInterface::class), 
            get(ResponseFactoryInterface::class),
            get(LoggerInterface::class),
            $_ENV["APP_ENV"] !== "prod"
        )
    ,
    LoggingMiddleware::class => autowire(),
    SecurityHeadersMiddleware::class => create()->constructor($_ENV["APP_ENV"] === "prod"),
];