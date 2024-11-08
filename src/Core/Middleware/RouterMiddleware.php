<?php

namespace Blog\Core\Middleware;

use Blog\Core\Router\Route;
use Blog\Core\Router\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RouterMiddleware implements MiddlewareInterface
{
    public function __construct(private Router $router)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $this->router->dispatch($request);
        if ($route instanceof Route) {
            $request = $request->withAttribute("_route", $route);
        }

        return $handler->handle($request);
    }
}
