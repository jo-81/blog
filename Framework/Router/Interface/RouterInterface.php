<?php

declare(strict_types=1);

namespace Framework\Router\Interface;

use Framework\Router\Exception\RouterException;

interface RouterInterface
{
    /**
     * @throws RouterException la route n'existe pas
     */
    public function getRoute(string $name): ?RouteInterface;

    /**
     * getRoutes
     *
     * @return RouteInterface[]
     */
    public function getRoutes(): array;

    public function registerRoute(RouteInterface $route): static;

    /**
     * @param  mixed[] $params
     *
     * @throws RouterException la route n'existe pas
     */
    public function generate(string $name, array $params = []): string;
}
