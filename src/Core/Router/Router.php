<?php

namespace Blog\Core\Router;

use Psr\Http\Message\RequestInterface;

final class Router
{
    /** @var array<string, array<int, Route>> */
    private array $routes = [];

    public function setRoutes(string $folder): void
    {
        if (! file_exists($folder)) {
            throw new \RuntimeException(sprintf("Le fichier %s n'existe pas", $folder));
        }

        $controllers = $this->getClassesFromDirectory($folder);

        foreach ($controllers as $controller) {
            $reflection = new \ReflectionClass($controller); /** @phpstan-ignore-line */

            foreach ($reflection->getMethods() as $method) {
                $attributes = $method->getAttributes(Route::class);

                foreach ($attributes as $attribute) {
                    $route = $attribute->newInstance();
                    $action = "$controller#" . $method->getName();
                    $this->addRoute($route->getMethod(), $route->getPath(), $route->getName(), $action);
                }
            }
        }
    }

    public function addRoute(string $method, string $path, string $name, string $action): self
    {
        $route = new Route($path, $name, $method);
        $route->setAction($action);

        $this->routes[$method][] = $route;

        return $this;
    }

    public function dispatch(RequestInterface $request): ?Route
    {
        $url = $request->getUri()->getPath();
        $method = $request->getMethod();

        if (! isset($this->routes[$method])) {
            return null;
        }

        foreach ($this->routes[$method] as $route) {
            if ($route->match($url) instanceof Route) {
                return $route;
            }
        }

        return null;
    }

    public function getRoute(string $name): ?Route
    {
        foreach ($this->routes as $method => $routes) {
            foreach ($routes as $route) {
                if ($route->getName() == $name) {
                    return $route;
                }
            }
        }

        return null;
    }

    /**
     * getClassesFromDirectory
     *
     * @param  string $directory
     * @return array<int, string>
     */
    private function getClassesFromDirectory(string $directory): array
    {
        $classes = [];
        /** @var array<int, \SplFileInfo> */
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory));

        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() == 'php') {
                $content = file_get_contents($file->getRealPath());
                $tokens = token_get_all($content); /** @phpstan-ignore-line */
                $namespace = '';

                for ($index = 0; $index < count($tokens); $index++) {
                    if ($tokens[$index][0] === T_NAMESPACE) {
                        $index += 2;
                        while (isset($tokens[$index]) && is_array($tokens[$index])) {
                            $namespace .= $tokens[$index][1];
                            $index++;
                        }
                    }
                    if ($tokens[$index][0] === T_CLASS) {
                        $index += 2;
                        $classes[] = $namespace . '\\' . $tokens[$index][1];

                        break;
                    }
                }
            }
        }

        return $classes;
    }
}
