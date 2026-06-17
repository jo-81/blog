<?php

namespace Framework;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Cœur du Framework.
 * * Responsable de la capture de la requête HTTP globale et de son acheminement
 * à travers la pile de middlewares pour produire une réponse HTTP.
 */
class Kernel
{
    /**
     * @param RequestHandlerInterface $routerDispatcher Le gestionnaire de la pile de middlewares.
     */
    public function __construct(private RequestHandlerInterface $routerDispatcher)
    {}

    /**
     * Gère la requête HTTP entrante et retourne la réponse générée par la pile de middlewares.
     *
     * @param ServerRequestInterface $request L'objet représentant la requête HTTP reçue.
     * @return ResponseInterface La réponse HTTP à renvoyer au navigateur.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->routerDispatcher->handle($request);
    }
}