<?php

namespace Framework\Http\Router;

/**
 * Représente une route enregistrée dans le framework.
 */
class Route
{
    /**
     * @param array<string> $methods Liste des méthodes HTTP (ex: ['GET', 'POST']).
     * @param string $path L'URI de la route (ex: '/blog/{id}').
     * @param mixed $handler Le contrôleur ou l'action à exécuter.
     * @param string|null $name Le nom optionnel de la route (pour la génération d'URL).
     */
    public function __construct(
        private array $methods,
        private string $path,
        private mixed $handler,
        private ?string $name = null
    ) {
        // Normalisation des méthodes en majuscules (GET, POST...)
        $this->methods = array_map('strtoupper', $methods);
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getHandler(): mixed
    {
        return $this->handler;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Permet de nommer la route de manière fluide (Fluent Interface).
     */
    public function name(string $name): self
    {
        $this->name = $name;
        
        return $this;
    }
}