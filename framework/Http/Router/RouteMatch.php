<?php

declare(strict_types=1);

namespace Framework\Http\Router;

/**
 * Représente le résultat d'une correspondance réussie.
 */
class RouteMatch
{
    /**
     * @param Route $route L'objet Route complet qui a correspondu à la requête.
     * @param array<string, string> $arguments Les variables extraites de l'URL (ex: ['id' => '42']).
     */
    public function __construct(
        private Route $route,
        private array $arguments = [],
    ) {}

    /**
     * Récupère l'objet Route d'origine.
     */
    public function getRoute(): Route
    {
        return $this->route;
    }

    /**
     * Récupère les variables dynamiques de l'URL.
     *
     * @return array<string, string>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Raccourci pratique pour récupérer le handler sans passer par getRoute().
     */
    public function getHandler(): mixed
    {
        return $this->route->getHandler();
    }
}
