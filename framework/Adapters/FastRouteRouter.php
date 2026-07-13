<?php

declare(strict_types=1);

namespace Framework\Adapters;

use RuntimeException;
use FastRoute\RouteCollector;
use Framework\Http\Router\Route;
use Framework\Http\Router\RouteMatch;
use Framework\Http\Router\RouterInterface;
use Psr\Http\Message\ServerRequestInterface;
use FastRoute\RouteParser\Std as RouteParser;
use Framework\Http\Exception\RouteNotFoundException;
use FastRoute\Dispatcher\GroupCountBased as Dispatcher;
use Framework\Http\Exception\MethodNotAllowedException;
use FastRoute\DataGenerator\GroupCountBased as DataGenerator;

/**
 * Class FastRouteRouter
 *
 * Adaptateur concret pour le moteur de routage nikic/FastRoute.
 * Implémente RouterInterface pour découpler le cœur du framework de la bibliothèque tierce.
 */
class FastRouteRouter implements RouterInterface
{
    /**
     * Le collecteur de routes natif de FastRoute.
     *
     * @var RouteCollector
     */
    private RouteCollector $routeCollector;

    private array $routes = [];

    /**
     * Constructeur du routeur.
     * Initialise les composants internes nécessaires à FastRoute.
     */
    public function __construct()
    {
        $this->routeCollector = new RouteCollector(
            new RouteParser(),
            new DataGenerator(),
        );
    }

    /**
     * @inheritDoc
     */
    public function addCollection(array $routes): self
    {
        foreach ($routes as $route) {
            $this->add($route);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function add(Route $route): Route
    {
        $this->routeCollector->addRoute(
            $route->getMethods(),
            $route->getPath(),
            $route, // Injection de l'objet Route complet en guise de handler personnalisé
        );

        if (!is_null($route->getName())) {
            $this->routes[$route->getName()] = $route;
        }

        return $route;
    }

    /**
     * @inheritDoc
     */
    public function get(string $path, mixed $handler): Route
    {
        return $this->add(new Route(['GET'], $path, $handler));
    }

    /**
     * @inheritDoc
     */
    public function post(string $path, mixed $handler): Route
    {
        return $this->add(new Route(['POST'], $path, $handler));
    }

    /**
     * @inheritDoc
     */
    public function put(string $path, mixed $handler): Route
    {
        return $this->add(new Route(['PUT'], $path, $handler));
    }

    /**
     * @inheritDoc
     */
    public function delete(string $path, mixed $handler): Route
    {
        return $this->add(new Route(['DELETE'], $path, $handler));
    }

    /**
     * @inheritDoc
     */
    public function match(ServerRequestInterface $request): RouteMatch
    {
        $httpMethod = $request->getMethod();
        $uri = $request->getUri()->getPath();

        // Compilation du dispatcher à la volée avec les données du collecteur
        $dispatcher = new Dispatcher($this->routeCollector->getData());

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw new RouteNotFoundException('404 Not Found', 404);

            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException('405 Method Not Allowed', 405);

            case Dispatcher::FOUND:
                /** * Récupération de l'objet Route d'origine passé en handler.
                 * @var Route $matchedRoute
                 */
                $matchedRoute = $routeInfo[1];

                /** * Variables dynamiques extraites de l'URL par FastRoute.
                 * @var array<string, string> $arguments
                 */
                $arguments = $routeInfo[2];

                return new RouteMatch($matchedRoute, $arguments);
        }

        throw new RuntimeException('Erreur interne de routage.');
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
