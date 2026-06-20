<?php

use App\Factories\HttpPipelineFactory;
use App\Factories\RouterFactory;
use App\Factories\ServerRequestFactory;
use Framework\Http\HttpPipelineInterface;
use Framework\Http\Router\RouterInterface;
use Psr\Http\Message\ServerRequestInterface;

return [
    HttpPipelineInterface::class => DI\factory(HttpPipelineFactory::class),
    ServerRequestInterface::class => DI\Factory(ServerRequestFactory::class),
    RouterInterface::class => DI\Factory(RouterFactory::class),
];