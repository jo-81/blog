<?php

namespace Framework\Factory;

use DI\NotFoundException;
use Framework\Router\RouterInterface;
use Psr\Container\ContainerInterface;

final class RouterFactory
{
    public function __invoke(ContainerInterface $container): RouterInterface
    {
        if (!$container->has('app.router_adapter')) {
            throw new NotFoundException('Aucun router de définit');
        }

        /** @var RouterInterface */
        $router = $container->get('app.router_adapter');
        if ($container->has('app.routes')) {
            /** @var array<int, array<string, string>> */
            $routes = $container->get('app.routes');
            foreach ($routes as $route) {
                $router->addRoute($route['name'], $route['path'], $route['callable'], $route['method'] ?? 'GET');
            }
        }

        return $router;
    }
}
