<?php

namespace App\Middlewares;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MessageTestMiddleware implements MiddlewareInterface
{
    /**
     * Processus obligatoire demandé par l'interface PSR-15
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // On crée une vraie réponse HTTP PSR-7 (Nyholm) avec un statut 200 (OK)
        // et le texte "Hello World !" dans le corps de la page.
        return new Response(200, [], '<h1>Hello World !</h1><p>Le Kernel et Relay fonctionnent parfaitement !</p>');
    }
}