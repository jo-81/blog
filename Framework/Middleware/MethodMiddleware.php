<?php

namespace Framework\Middleware;

use Framework\Exception\MethodNotAllowedException;
use Framework\Router\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class MethodMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Route */
        $route = $request->getAttribute('_route');
        if ($request->getMethod() != $route->getMethod()) {
            throw new MethodNotAllowedException(\sprintf("La méthode %s n'est pas permisse", $request->getMethod()));
        }

        return $handler->handle($request);
    }
}
