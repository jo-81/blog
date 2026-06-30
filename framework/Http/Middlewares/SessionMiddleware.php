<?php

namespace Framework\Http\Middlewares;

use Framework\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SessionMiddleware implements MiddlewareInterface
{
    public function __construct(private SessionInterface $session)
    {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->session->start();
        $request = $request->withAttribute('session', $this->session);
        $response = $handler->handle($request);

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        return $response;
    }
}