<?php

namespace Framework\Http\Middlewares;

use RuntimeException;
use Framework\Http\Router\RouteMatch;
use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandlerMiddleware implements MiddlewareInterface
{
    /**
     * @param Container $container Le conteneur PHP-DI (qui dispose de la méthode call).
     */
    public function __construct(private Container $container) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var RouteMatch|null $routeMatch */
        $routeMatch = $request->getAttribute('_route');

        if (!$routeMatch instanceof RouteMatch) {
            throw new RuntimeException("Erreur d'architecture : L'attribut '_route' est manquant.");
        }

        // On extrait le handler (ex: [PortfolioController::class, 'show'] ou une Closure)
        $routeHandler = $routeMatch->getHandler();
        
        // On fusionne la requête et les arguments d'URL pour les injecter dans le contrôleur
        $parameters = array_merge(
            ['request' => $request], // Permet au contrôleur de récupérer la $request par son nom de variable
            $routeMatch->getArguments() // Injecte les variables d'URL (ex: 'id' => 4)
        );

        try {
            return $this->container->call($routeHandler, $parameters);
        } catch (\Throwable $e) {
            throw new RuntimeException(
                "Impossible d'exécuter le handler de la route : " . $e->getMessage(), 
                0, 
                $e
            );
        }
    }
}