<?php

declare(strict_types=1);

namespace Framework\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Http\Interface\AppResponseInterface;
use Framework\Middleware\Exception\MiddlewareException;

/**
 * Gère l'exécution d'une pile de middlewares selon l'ordre défini dans le registre.
 *
 * Cette classe implémente le pattern de middleware (PSR-15) et permet d'enchaîner
 * l'exécution de plusieurs middlewares avant d'appeler le handler final.
 *
 * @package Framework\Middleware
 */
final class MiddlewareHandler implements RequestHandlerInterface
{
    /**
     * Handler final appelé une fois tous les middlewares exécutés.
     *
     * @var RequestHandlerInterface|null
     */
    private ?RequestHandlerInterface $finalHandler = null;

    /**
     * Constructeur.
     *
     * @param MiddlewareRegistry $middlewareRegistry Registre contenant les middlewares à exécuter.
     */
    public function __construct(private MiddlewareRegistry $middlewareRegistry)
    {
    }

    /**
     * Démarre l'exécution de la pile de middlewares.
     *
     * @param ServerRequestInterface $request La requête HTTP à traiter.
     *
     * @return AppResponseInterface La réponse générée par le handler final ou un middleware.
     */
    public function handle(ServerRequestInterface $request): AppResponseInterface
    {
        return $this->dispatch($request, 0);
    }

    /**
     * Exécute le middleware à l'index donné, puis passe au suivant ou au handler final.
     *
     * @param ServerRequestInterface $request La requête HTTP à traiter.
     * @param int                    $index   Index du middleware à exécuter.
     *
     * @return AppResponseInterface La réponse générée.
     *
     * @throws MiddlewareException Si aucun handler final n'a été défini.
     */
    public function dispatch(ServerRequestInterface $request, int $index): AppResponseInterface
    {
        if (null === $this->finalHandler) {
            throw new MiddlewareException("Aucun final handler n'a été défini.");
        }

        $middleware = $this->middlewareRegistry->getMiddleware($index);
        if (null === $middleware) {
            /** @var AppResponseInterface */
            return $this->finalHandler->handle($request);
        }

        $nextHandler = new MiddlewareNextHandler($this, $index + 1);

        /** @var AppResponseInterface */
        return $middleware->process($request, $nextHandler);
    }

    /**
     * Définit le handler final appelé après tous les middlewares.
     *
     * @param RequestHandlerInterface $finalHandler Le handler final.
     *
     * @return self Retourne l'instance courante pour permettre le chaînage.
     */
    public function setFinalHandler(RequestHandlerInterface $finalHandler): self
    {
        $this->finalHandler = $finalHandler;

        return $this;
    }
}
