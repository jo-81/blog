<?php

declare(strict_types=1);

namespace Framework\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Framework\Middleware\Exception\MiddlewareException;

/**
 * Registre des middlewares utilisés par l'application.
 *
 * Cette classe permet d'enregistrer, récupérer et vérifier la présence de middlewares
 * utilisés par le système de traitement des requêtes. Chaque middleware doit implémenter
 * l'interface PSR-15 MiddlewareInterface.
 *
 * @package Framework\Middleware
 */
final class MiddlewareRegistry
{
    /**
     * Liste des middlewares enregistrés dans le registre.
     *
     * @var MiddlewareInterface[]
     */
    private array $middlewares = [];

    /**
     * Enregistre un middleware dans le registre.
     *
     * Empêche l'ajout de doublons : un middleware de la même classe ne peut être
     * enregistré qu'une seule fois.
     *
     * @param MiddlewareInterface $middleware Le middleware à ajouter.
     *
     * @throws MiddlewareException Si le middleware est déjà enregistré.
     *
     * @return self Retourne l'instance courante pour chaînage fluide.
     */
    public function registerMiddleware(MiddlewareInterface $middleware): self
    {
        if ($this->hasMiddleware($middleware)) {
            throw new MiddlewareException(sprintf(
                "Le middleware %s est déjà présent.",
                get_class($middleware)
            ));
        }

        $this->middlewares[] = $middleware;

        return $this;
    }

    /**
     * Vérifie si un middleware de la même classe est déjà enregistré.
     *
     * @param MiddlewareInterface $middleware Le middleware à vérifier.
     * @return bool True si un middleware de la même classe est déjà présent, sinon false.
     */
    public function hasMiddleware(MiddlewareInterface $middleware): bool
    {
        $className = get_class($middleware);
        foreach ($this->middlewares as $registered) {
            if ($registered instanceof $className) {
                return true;
            }
        }

        return false;
    }

    /**
     * Retourne la liste complète des middlewares enregistrés.
     *
     * @return MiddlewareInterface[] Tableau contenant les middlewares enregistrés.
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * Récupère un middleware spécifique par son index dans le registre.
     *
     * @param int $index Index du middleware à récupérer.
     * @return MiddlewareInterface|null Le middleware correspondant, ou null s'il n'existe pas.
     */
    public function getMiddleware(int $index): ?MiddlewareInterface
    {
        return $this->middlewares[$index] ?? null;
    }
}
