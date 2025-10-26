<?php

declare(strict_types=1);

namespace Framework\Router;

use Framework\Trait\ContainerTrait;
use Psr\Container\ContainerInterface;
use Framework\Router\Interface\RouterInterface;
use Framework\Router\Interface\RouterFactoryInterface;

class RouterFactory
{
    use ContainerTrait;

    public function __invoke(ContainerInterface $container, RouterFactoryInterface $routerFactory): RouterInterface
    {
        $routes = $this->get($container, "app.routes", []);

        return $routerFactory->factory($routes);
    }
}
