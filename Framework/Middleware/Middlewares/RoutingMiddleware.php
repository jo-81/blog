<?php

declare(strict_types=1);

namespace Framework\Middleware\Middlewares;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Router\Interface\RouterInterface;
use Framework\Http\Interface\AppRequestInterface;
use Framework\Http\Interface\AppResponseInterface;
use Framework\Router\Exception\RouteNotFoundException;

/**
 * Middleware de résolution de route.
 *
 * Intercepte la requête HTTP entrante, utilise le routeur pour déterminer la route correspondante,
 * puis ajoute la route en tant qu'attribut de la requête sous la clé définie par ATTRIBUTE_NAME.
 * Si aucune route ne correspond, une exception RouteNotFoundException est levée.
 *
 * @package Framework\Middleware\Middlewares
 */
final class RoutingMiddleware implements MiddlewareInterface
{
    /**
     * Nom de l'attribut dans la requête HTTP pour stocker la route résolue.
     */
    public const ATTRIBUTE_NAME = "_app__route";

    /**
     * Initialise le middleware avec le routeur.
     *
     * @param RouterInterface $router Router pour la résolution des routes.
     */
    public function __construct(private RouterInterface $router)
    {
    }

    /**
     * Résout la route basée sur la requête HTTP et ajoute l'objet route en attribut.
     *
     * @param AppRequestInterface $request Requête HTTP entrante.
     * @param RequestHandlerInterface $handler Handler suivant dans la chaîne.
     *
     * @return AppResponseInterface Réponse HTTP générée.
     *
     * @throws RouteNotFoundException Si aucune route ne correspond à la requête.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): AppResponseInterface
    {
        $route = $this->router->match($request);
        if (null === $route) {
            throw new RouteNotFoundException();
        }

        $request = $request->withAttribute(self::ATTRIBUTE_NAME, $route);

        /** @var AppResponseInterface */
        return $handler->handle($request);
    }
}
