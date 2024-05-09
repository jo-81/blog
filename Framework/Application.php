<?php

namespace Framework;

use Framework\Middleware\MiddlewareHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Application
{
    private MiddlewareHandler $middlewareHandler;

    public function setMiddlewareHandler(MiddlewareHandler $middlewareHandler): self
    {
        $this->middlewareHandler = $middlewareHandler;

        return $this;
    }

    public function run(ServerRequestInterface $request): ResponseInterface
    {
        return $this->middlewareHandler->handle($request);
    }
}
