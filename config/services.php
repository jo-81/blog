<?php

use App\Factories\HttpPipelineFactory;
use App\Factories\ServerRequestFactory;
use Framework\Http\HttpPipelineInterface;
use Psr\Http\Message\ServerRequestInterface;

return [
    HttpPipelineInterface::class => DI\factory(HttpPipelineFactory::class),
    ServerRequestInterface::class => DI\Factory(ServerRequestFactory::class),
];