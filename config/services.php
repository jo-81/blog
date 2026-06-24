<?php

use Psr\Log\LoggerInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Framework\Factories\LoggerFactory;
use Framework\Factories\RouterFactory;
use Framework\Http\HttpPipelineInterface;
use Framework\Http\Router\RouterInterface;
use Framework\Factories\HttpPipelineFactory;
use Framework\Factories\TwigRendererFactory;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Factories\ServerRequestFactory;
use Framework\Renderer\RendererInterface;
use Psr\Http\Message\ResponseFactoryInterface;

return [
    HttpPipelineInterface::class => DI\factory(HttpPipelineFactory::class),
    ServerRequestInterface::class => DI\factory(ServerRequestFactory::class),
    RouterInterface::class => DI\factory(RouterFactory::class),
    ResponseFactoryInterface::class => DI\get(Psr17Factory::class),
    LoggerInterface::class => DI\factory(LoggerFactory::class),
    RendererInterface::class => DI\factory(TwigRendererFactory::class),
];