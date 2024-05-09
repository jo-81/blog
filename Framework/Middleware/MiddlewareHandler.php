<?php

namespace Framework\Middleware;

use Framework\Middleware\Exception\MiddlewareNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class MiddlewareHandler implements RequestHandlerInterface
{
    private int $position = 0;
    /** @var array<int, MiddlewareInterface> */
    private array $middlewares = [];

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $middleware = $this->getMiddleware();
        if (is_null($middleware)) {
            throw new MiddlewareNotFoundException('Aucuns middlewares de déclarés');
        }

        return $middleware->process($request, $this);
    }

    public function getMiddleware(): ?MiddlewareInterface
    {
        if (!isset($this->middlewares[$this->position])) {
            return null;
        }

        $middleware = $this->middlewares[$this->position];
        ++$this->position;

        return $middleware;
    }

    public function add(MiddlewareInterface $middleware): self
    {
        $this->middlewares[] = $middleware;

        return $this;
    }
}
