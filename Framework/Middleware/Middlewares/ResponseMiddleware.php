<?php

declare(strict_types=1);

namespace Framework\Middleware\Middlewares;

use Invoker\InvokerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Invoker\Exception\InvocationException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Http\Interface\AppRequestInterface;

/**
 * Middleware responsable de l'invocation de la cible et de la génération de la réponse HTTP.
 *
 * Utilise l'invoker pour appeler la méthode de contrôleur résolue par les middlewares précédents.
 * Récupère les paramètres depuis les attributs de la requête, appelle la cible,
 * vérifie que le retour est une instance de ResponseInterface,
 * et renvoie la réponse correspondante.
 *
 * En cas d'erreur lors de l'invocation, une RuntimeException est levée.
 *
 * @package Framework\Middleware\Middlewares
 */
final class ResponseMiddleware implements MiddlewareInterface
{
    /**
     * Constructeur.
     *
     * @param InvokerInterface $invoker L'invocateur responsable de l'exécution des callbacks.
     */
    public function __construct(private InvokerInterface $invoker)
    {
    }

    /**
     * Traite la requête en invoquant la cible avec les paramètres et retourne la réponse HTTP.
     *
     * @param AppRequestInterface $request Requête HTTP entrante.
     * @param RequestHandlerInterface $handler Prochain middleware dans la chaîne (non utilisé ici).
     *
     * @return ResponseInterface Réponse HTTP générée par la cible.
     *
     * @throws \RuntimeException Si l'invocation échoue ou si la réponse n'est pas valide.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $target = $request->getAttribute('_app__target');
        $parameters = $request->getAttribute('_app__parameters', []);

        try {
            $response = $this->invoker->call($target, $parameters);
        } catch (InvocationException $th) {
            throw new \RuntimeException($th->getMessage());
        }

        if (!$response instanceof ResponseInterface) {
            throw new \RuntimeException('Le callback doit retourner une instance de ResponseInterface.');
        }

        return $response;
    }
}
