<?php

declare(strict_types=1);

namespace Framework\Middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Http\Interface\AppResponseInterface;

/**
 * Représente le handler suivant dans la chaîne d'exécution des middlewares.
 *
 * Cette classe est utilisée pour enchaîner l'exécution des middlewares.
 * Chaque instance de MiddlewareNextHandler permet d'appeler le prochain middleware
 * dans la pile gérée par MiddlewareHandler.
 * 
 * @package Framework\Middlewares
 */
class MiddlewareNextHandler implements RequestHandlerInterface
{
    /**
     * Initialise le handler du prochain middleware.
     *
     * @param MiddlewareHandler $handler Référence vers le gestionnaire principal de middlewares.
     * @param int $index Index du prochain middleware à exécuter.
     */
    public function __construct(
        private MiddlewareHandler $handler,
        private int $index
    ) {
    }

    /**
     * Appelle le middleware suivant dans la pile d'exécution.
     *
     * @param ServerRequestInterface $request La requête HTTP actuelle.
     *
     * @return AppResponseInterface Réponse générée par le prochain middleware ou le handler final.
     */
    public function handle(ServerRequestInterface $request): AppResponseInterface
    {
        return $this->handler->dispatch($request, $this->index);
    }
}
