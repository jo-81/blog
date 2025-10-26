<?php

declare(strict_types=1);

namespace Tests\Framework\Router;

use PHPUnit\Framework\TestCase;
use Framework\Router\RouterFactory;
use Psr\Container\ContainerInterface;
use Framework\Router\Interface\RouterInterface;
use Framework\Router\Interface\RouterFactoryInterface;

class RouterFactoryTest extends TestCase
{
    private RouterFactory $routerFactory;

    protected function setUp(): void
    {
        $this->routerFactory = new RouterFactory();
    }

    /**
     * testReturnRouterInterface
     *
     * @return void
     */
    public function testReturnRouterInterface(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $routerFactory = $this->createMock(RouterFactoryInterface::class);
        $router = $this->routerFactory->__invoke($container, $routerFactory);

        $this->assertInstanceOf(RouterInterface::class, $router);
    }
}
