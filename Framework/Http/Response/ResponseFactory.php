<?php

declare(strict_types=1);

namespace Framework\Http\Response;

use Psr\Container\ContainerInterface;
use Framework\Http\Interface\ResponseInterface;
use Framework\Http\Interface\ResponseFactoryInterface;

class ResponseFactory
{
    public function __invoke(ContainerInterface $container, ResponseFactoryInterface $responseFactory): ResponseInterface
    {
        return $responseFactory->factory();
    }
}
