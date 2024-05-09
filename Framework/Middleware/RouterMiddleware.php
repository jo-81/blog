<?php

namespace Framework\Middleware;

use Framework\Router\Exception\RouteNotFoundException;
use Framework\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouterMiddleware implements MiddlewareInterface
{
    public function __construct(private RouterInterface $router)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $this->router->match($request->getUri()->getPath());
        if (is_null($route)) {
            throw new RouteNotFoundException('Aucune route correspondante');
        }

        $request = $request->withAttribute('_route', $route);

        return $handler->handle($request);
    }
}
