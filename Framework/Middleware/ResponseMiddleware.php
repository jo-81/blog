<?php

namespace Framework\Middleware;

use Framework\Router\Exception\RouteNotFoundException;
use Framework\Router\Route;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ResponseMiddleware implements MiddlewareInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $request->getAttribute('_route');
        if (!$route instanceof Route) {
            throw new RouteNotFoundException('Pas de route');
        }

        $callback = $route->getCallable();
        $parameters = $route->getParameters();

        /** @var ResponseInterface */
        $response = call_user_func_array($this->getCallback($callback), $parameters);

        return $response;
    }

    private function getCallback(string $callback): callable
    {
        $callbacks = explode('#', $callback);
        $controller = $callbacks[0];
        $method = $callbacks[1];

        if ($this->container->has($controller)) {
            $controllerInstance = $this->container->get($controller);
        } else {
            if (class_exists($controller)) {
                $controllerInstance = new $controller();
            } else {
                throw new \Exception("Le controller $controller n'existe pas");
            }
        }

        if (!method_exists($controller, $method)) {
            throw new \Exception("La méthode $method du controller $controller n'existe pas");
        }

        /* @phpstan-ignore-next-line */
        return [$controllerInstance, $method];
    }
}
