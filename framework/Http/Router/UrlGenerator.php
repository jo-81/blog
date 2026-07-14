<?php

declare(strict_types=1);

namespace Framework\Http\Router;

class UrlGenerator
{
    public function __construct(private RouterInterface $router) {}

    public function generate(string $name, array $params = []): string
    {
        $routes = $this->router->getRoutes();

        if (!isset($routes[$name])) {
            throw new \InvalidArgumentException("La route nommée '{$name}' n'existe pas.");
        }

        $path = $routes[$name]->getPath();

        // On remplace les {param} ou {param:\d+} par les valeurs fournies
        foreach ($params as $key => $value) {
            // Cette regex attrape {id} ou {id:\d+}
            $path = preg_replace('#\{' . $key . '(:.*)?\}#U', (string) $value, $path);
        };

        return $path;
    }
}
