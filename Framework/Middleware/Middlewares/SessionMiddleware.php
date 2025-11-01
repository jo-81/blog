<?php

declare(strict_types=1);

namespace Framework\Middleware\Middlewares;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Http\Interface\AppRequestInterface;
use Framework\Session\Interface\SessionInterface;
use Framework\Http\Interface\AppResponseInterface;

/**
 * Middleware qui gère le cycle de vie de la session HTTP.
 *
 * Démarre la session avant le traitement de la requête, ajoute l'objet session
 * à l'attribut de la requête pour qu'il soit accessible dans le reste de la chaîne,
 * puis sauvegarde la session après traitement.
 *
 * @package Framework\Middleware\Middlewares
 */
final class SessionMiddleware implements MiddlewareInterface
{
    /**
     * Initialise le middleware avec l'interface de session.
     *
     * @param SessionInterface $session Objet de gestion de session.
     */
    public function __construct(private SessionInterface $session)
    {
    }

    /**
     * Démarre la session, ajoute la session à la requête, délègue au handler suivant,
     * puis sauvegarde la session.
     *
     * @param AppRequestInterface $request Requête HTTP entrante.
     * @param RequestHandlerInterface $handler Prochain handler dans la chaîne.
     * @return AppResponseInterface Réponse HTTP générée.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): AppResponseInterface
    {
        $this->session->start();

        $request = $request->withAttribute('session', $this->session);

        $response = $handler->handle($request);

        $this->session->save();

        /** @var AppResponseInterface */
        return $response;
    }
}
