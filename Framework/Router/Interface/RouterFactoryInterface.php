<?php

declare(strict_types=1);

namespace Framework\Router\Interface;

interface RouterFactoryInterface
{
    /**
     * factory
     *
     * @param  RouteInterface[] $routes
     */
    public function factory(array $routes): RouterInterface;
}
