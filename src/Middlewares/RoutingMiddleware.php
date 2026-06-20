<?php

namespace App\Middlewares;

use Framework\Http\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class RoutingMiddleware
 *
 * Middleware chargé d'analyser la requête HTTP entrante pour identifier la route correspondante.
 * Si une correspondance est trouvée, l'objet RouteMatch résultant est injecté dans les 
 * attributs de la requête pour être exploité par les composants suivants.
 */
class RoutingMiddleware implements MiddlewareInterface
{
    /**
     * Le moteur de routage de l'application.
     *
     * @var RouterInterface
     */
    private RouterInterface $router;

    /**
     * Constructeur du middleware de routage.
     *
     * @param RouterInterface $router Le moteur de routage du framework.
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }    

    /**
     * Exécute le traitement du middleware.
     * Analyse la requête et tente de faire correspondre une route. En cas d'échec (404/405), 
     * l'exception levée par le routeur est propagée vers le haut du pipeline.
     *
     * @param ServerRequestInterface $request La requête HTTP entrante.
     * @param RequestHandlerInterface $handler Le gestionnaire de requêtes suivant dans le pipeline.
     * @return ResponseInterface La réponse HTTP générée.
     * @throws \Framework\Http\Exception\RouteNotFoundException Si l'URL demandée n'existe pas.
     * @throws \Framework\Http\Exception\MethodNotAllowedException Si l'URL existe mais avec une méthode HTTP différente.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $request->withAttribute('_route', $this->router->match($request));

        return $handler->handle($request);
    }
}