<?php

namespace Framework\Router\Adapter;

use Framework\Router\Route;
use Framework\Router\RouterInterface;

final class AltoRouterAdapter implements RouterInterface
{
    public function __construct(private \AltoRouter $router)
    {
    }

    public function match(string $path): ?Route
    {
        $route = $this->router->match($path);
        if (!$route || !(isset($route['name'], $route['params']))) {
            return null;
        }

        return $this->getRoute($route['name'], $route['params']);
    }

    public function addRoute(string $name, string $path, string $callback, string $method = 'GET'): self
    {
        $this->router->map($method, $path, $callback, $name);

        return $this;
    }

    /**
     * getRoute.
     *
     * @param array<string, mixed> $parameters
     */
    public function getRoute(string $name, array $parameters = []): ?Route
    {
        foreach ($this->router->getRoutes() as $route) {
            if ($name == $route[3]) {
                return new Route($route[3], $route[1], $route[2], $route[0], $parameters);
            }
        }

        return null;
    }
}
