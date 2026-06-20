<?php

use App\Factories\RouterFactory;
use App\Middlewares\WhoopsMiddleware;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use App\Factories\HttpPipelineFactory;
use App\Factories\ServerRequestFactory;
use Framework\Http\HttpPipelineInterface;
use Framework\Http\Router\RouterInterface;
use App\Middlewares\ErrorHandlingMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;

return [
    HttpPipelineInterface::class => DI\factory(HttpPipelineFactory::class),
    ServerRequestInterface::class => DI\Factory(ServerRequestFactory::class),
    RouterInterface::class => DI\Factory(RouterFactory::class),
    ResponseFactoryInterface::class => DI\get(Psr17Factory::class),

    ErrorHandlingMiddleware::class => fn(ContainerInterface $c) => new ErrorHandlingMiddleware(
        $c->get(ResponseFactoryInterface::class),
        $c->get('settings.debug')
    ),

    WhoopsMiddleware::class => fn(ContainerInterface $c) => new WhoopsMiddleware(
        $c->get(ResponseFactoryInterface::class),
        $c->get('settings.debug')
    ),
];