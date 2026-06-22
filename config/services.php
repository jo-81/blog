<?php

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use Framework\Factories\RouterFactory;
use Framework\Http\HttpPipelineInterface;
use Framework\Http\Router\RouterInterface;
use Framework\Factories\HttpPipelineFactory;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Factories\ServerRequestFactory;
use Psr\Http\Message\ResponseFactoryInterface;
use Framework\Http\Middlewares\WhoopsMiddleware;
use Framework\Http\Middlewares\ErrorHandlingMiddleware;



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