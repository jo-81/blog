<?php

namespace Blog\Core\Middleware;

use Blog\Core\Router\Route;
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
        $route = $request->getAttribute('_route', null);
        if (!($route instanceof Route)) {
            return $handler->handle($request);
        }

        $controller = $route->getAction(0);
        $method = $route->getAction(1);

        if (! $this->container->has($controller)) {
            $constrollerInstance = new $controller();
        } else {
            $constrollerInstance = $this->container->get($controller);
        }

        return call_user_func_array([$constrollerInstance, $method], []); /** @phpstan-ignore-line */
    }
}
