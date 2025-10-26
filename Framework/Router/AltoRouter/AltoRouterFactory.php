<?php

declare(strict_types=1);

namespace Framework\Router\AltoRouter;

use AltoRouter;
use Framework\Router\Interface\RouteInterface;
use Framework\Router\Interface\RouterInterface;
use Framework\Router\Interface\RouterFactoryInterface;

class AltoRouterFactory implements RouterFactoryInterface
{
    public function factory(array $routes): RouterInterface
    {
        $altoRouter = new AltoRouter($this->generateRoutes($routes));

        return new AltoRouterRouter($altoRouter);
    }

    /**
     * @param  RouteInterface[] $routes
     *
     * @return list<array<string, string>>
     */
    private function generateRoutes(array $routes): array
    {
        $altoRoutes = [];
        foreach ($routes as $route) {
            $altoRoutes[] = [
                'method' => $route->getMethod(),
                'route' => $route->getPath(),
                'name' => $route->getName(),
                'target' => $route->getTarget(),
            ];
        }

        return $altoRoutes;
    }
}
