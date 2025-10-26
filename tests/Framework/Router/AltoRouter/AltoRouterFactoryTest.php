<?php

declare(strict_types=1);

namespace Tests\Framework\Router\AltoRouter;

use Framework\Router\Route;
use PHPUnit\Framework\TestCase;
use Framework\Router\Interface\RouterInterface;
use Framework\Router\AltoRouter\AltoRouterFactory;

class AltoRouterFactoryTest extends TestCase
{
    public function testFactory(): void
    {
        $routes = [
            new Route("homepage", "/"),
        ];
        $altoRouterFactory = new AltoRouterFactory();
        $router = $altoRouterFactory->factory($routes);

        $this->assertInstanceOf(RouterInterface::class, $router);
    }
}
