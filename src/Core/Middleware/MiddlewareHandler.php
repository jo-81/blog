<?php

namespace Blog\Core\Middleware;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class MiddlewareHandler implements RequestHandlerInterface
{
    private int $index = 0;

    /**
     * @param  array<int, MiddlewareInterface> $middlewares
     */
    public function __construct(private array $middlewares = [])
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $middleware = $this->getMiddleware();
        if (is_null($middleware)) {
            return new Response();
        }

        return $middleware->process($request, $this);
    }

    public function getMiddleware(): ?MiddlewareInterface
    {
        if (! array_key_exists($this->index, $this->middlewares)) {
            return null;
        }
        $this->index++;

        return $this->middlewares[$this->index];
    }
}
