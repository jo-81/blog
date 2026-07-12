<?php

declare(strict_types=1);

namespace Framework\Http\Router;

use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Exception\RouteNotFoundException;
use Framework\Http\Exception\MethodNotAllowedException;

/**
 * Interface RouterInterface
 *
 * Définit le contrat global pour le système de routage du framework.
 * Permet l'enregistrement de routes et la correspondance avec une requête HTTP entrante.
 */
interface RouterInterface
{
    /**
     * Enregistre une configuration de Route complète dans le routeur.
     *
     * @param Route $route L'instance de la route à ajouter.
     * @return Route L'instance de la route ajoutée (permet le chaînage, ex: ->name()).
     */
    public function add(Route $route): Route;

    /**
     * Raccourci fluide pour enregistrer une route GET.
     *
     * @param string $path L'URI de la route (ex: '/blog/{id}').
     * @param mixed $handler Le contrôleur ou l'action à exécuter (callable, string, array).
     * @return Route L'instance de la route créée.
     */
    public function get(string $path, mixed $handler): Route;

    /**
     * Raccourci fluide pour enregistrer une route POST.
     *
     * @param string $path L'URI de la route.
     * @param mixed $handler Le contrôleur ou l'action à exécuter.
     * @return Route L'instance de la route créée.
     */
    public function post(string $path, mixed $handler): Route;

    /**
     * Raccourci fluide pour enregistrer une route PUT.
     *
     * @param string $path L'URI de la route.
     * @param mixed $handler Le contrôleur ou l'action à exécuter.
     * @return Route L'instance de la route créée.
     */
    public function put(string $path, mixed $handler): Route;

    /**
     * Raccourci fluide pour enregistrer une route DELETE.
     *
     * @param string $path L'URI de la route.
     * @param mixed $handler Le contrôleur ou l'action à exécuter.
     * @return Route L'instance de la route créée.
     */
    public function delete(string $path, mixed $handler): Route;

    /**
     * Enregistre une collection de routes d'un seul coup.
     *
     * @param array<Route> $routes Liste des instances de Route à enregistrer.
     * @return self L'instance du routeur pour le chaînage.
     */
    public function addCollection(array $routes): self;

    /**
     * Analyse la requête PSR-7 entrante pour trouver une route correspondante.
     *
     * @param ServerRequestInterface $request La requête HTTP entrante.
     * @return RouteMatch Le résultat contenant les informations de la route trouvée et ses arguments.
     * @throws RouteNotFoundException Si aucune route ne correspond à l'URI (Erreur 404).
     * @throws MethodNotAllowedException Si l'URI correspond mais pas avec la méthode HTTP actuelle (Erreur 405).
     */
    public function match(ServerRequestInterface $request): RouteMatch;

    public function getRoutes(): array;
}
