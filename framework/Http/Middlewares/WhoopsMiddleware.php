<?php

namespace Framework\Http\Middlewares;

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class WhoopsMiddleware
 *
 * Middleware de débogage pour l'environnement de développement.
 * Si le mode debug est activé, il intercepte les exceptions non gérées du pipeline
 * et génère une réponse visuelle interactive via filp/whoops.
 */
class WhoopsMiddleware implements MiddlewareInterface
{
    /**
     * @param ResponseFactoryInterface $responseFactory La fabrique de réponses PSR-17.
     * @param bool $isDebug Flag indiquant si le mode débogage (APP_DEBUG) est actif.
     */
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private bool $isDebug
    ) {}

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Si nous ne sommes pas en mode debug, on passe directement au middleware suivant (transparent)
        if (!$this->isDebug) {
            return $handler->handle($request);
        }

        try {
            return $handler->handle($request);
        } catch (\Throwable $exception) {
            // 1. Instanciation locale de Whoops
            $whoops = new Run();
            $prettyPageHandler = new PrettyPageHandler();

            // Optionnel : Ajoute les attributs PSR-7 de la requête (comme '_route') dans l'interface Whoops
            $prettyPageHandler->addDataTable('PSR-7 Request Attributes', $request->getAttributes());
            $whoops->pushHandler($prettyPageHandler);

            // 2. Configuration pour récupérer le flux au lieu de l'afficher directement via PHP (impératif pour PSR-7)
            $whoops->allowQuit(false);
            $whoops->writeToOutput(false);

            // 3. Génération du HTML de la Stack Trace
            $htmlOutput = $whoops->handleException($exception);

            // 4. Encapsulation dans une réponse PSR-7 propre (Erreur 500)
            $response = $this->responseFactory->createResponse(500);
            $response->getBody()->write($htmlOutput);

            return $response;
        }
    }
}