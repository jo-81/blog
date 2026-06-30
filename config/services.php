<?php

use Framework\Database\EntityManagerInterface;
use Framework\Database\UserRepositoryInterface;
use Framework\Factories\CycleORMFactory;
use Framework\Factories\CycleUserFactory;
use Framework\Factories\HttpPipelineFactory;
use Framework\Factories\LoggerFactory;
use Framework\Factories\PHPSessionFactory;
use Framework\Factories\RouterFactory;
use Framework\Factories\ServerRequestFactory;
use Framework\Factories\TwigRendererFactory;
use Framework\Http\HttpPipelineInterface;
use Framework\Http\Router\RouterInterface;
use Framework\Renderer\RendererInterface;
use Framework\Session\SessionInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

return [
    HttpPipelineInterface::class => DI\factory(HttpPipelineFactory::class),
    ServerRequestInterface::class => DI\factory(ServerRequestFactory::class),
    RouterInterface::class => DI\factory(RouterFactory::class),
    ResponseFactoryInterface::class => DI\get(Psr17Factory::class),
    LoggerInterface::class => DI\factory(LoggerFactory::class),
    RendererInterface::class => DI\factory(TwigRendererFactory::class),
    EntityManagerInterface::class => DI\factory(CycleORMFactory::class),
    UserRepositoryInterface::class => DI\factory(CycleUserFactory::class),
    SessionInterface::class => DI\factory(PHPSessionFactory::class),
];