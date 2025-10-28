<?php

declare(strict_types=1);

namespace Framework\Router\AltoRouter;

use AltoRouter;
use Framework\Router\Route;
use Framework\Router\Interface\RouteInterface;
use Framework\Router\Exception\RouterException;
use Framework\Router\Interface\RouterInterface;
use Framework\Http\Interface\AppRequestInterface;

class AltoRouterRouter implements RouterInterface
{
    public function __construct(private AltoRouter $altoRouter)
    {
    }

    public function registerRoute(RouteInterface $route): static
    {
        $this->altoRouter->map($route->getMethod(), $route->getPath(), $route->getTarget(), $route->getName());

        return $this;
    }

    /**
     * getRoutes
     *
     * @return RouteInterface[]
     */
    public function getRoutes(): array
    {
        $routes = [];
        foreach ($this->altoRouter->getRoutes() as $route) {
            $routes[$route[3]] = new Route($route[3], $route[1], $route[2], $route[0]);
        }

        return $routes;
    }

    /**
     * getRoute
     *
     * @throws RouterException la route n'existe pas
     */
    public function getRoute(string $name): RouteInterface
    {
        $routes = $this->getRoutes();
        if (! array_key_exists($name, $routes)) {
            throw new RouterException(\sprintf("La route %s n'existe pas.", $name));
        }

        return $routes[$name];
    }

    /**
     * @param  mixed[] $params
     *
     * @throws RouterException la route n'existe pas
     */
    public function generate(string $name, array $params = []): string
    {
        try {
            return $this->altoRouter->generate($name, $params);
        } catch (\Throwable $th) {
            throw new RouterException(\sprintf("La route %s n'existe pas.", $name));
        }
    }

    public function match(AppRequestInterface $request): ?RouteInterface
    {
        $route = $this->altoRouter->match($request->getUri()->getPath(), $request->getMethod());
        if (!is_array($route)) {
            return null;
        }

        $routeInterface = $this->getRoute($route['name']);
        $routeInterface->setParameters($route['params']);

        return $routeInterface;
    }
}
