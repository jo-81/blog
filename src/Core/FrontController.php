<?php

namespace Blog\Core;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class FrontController
{
    public function __construct(private RequestHandlerInterface $middelwareHandle)
    {
    }

    public function start(ServerRequestInterface $request): ResponseInterface
    {
        return $this->middelwareHandle->handle($request);
    }
}
