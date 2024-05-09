<?php

namespace Framework\Router;

interface RouterInterface
{
    public function match(string $path): ?Route;

    public function addRoute(string $name, string $path, string $callback, string $method = 'GET'): self;

    public function getRoute(string $name): ?Route;
}
