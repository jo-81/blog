<?php

declare(strict_types=1);

namespace Framework\Http\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Framework\Http\Exception\RouteNotFoundException;
use Framework\Http\Exception\MethodNotAllowedException;

/**
 * Class ErrorHandlingMiddleware
 *
 * Gestionnaire d'erreurs applicatif global.
 * Intercepte les exceptions de routage (404/405) et les pannes serveurs (500).
 * En mode debug, il laisse remonter les erreurs 500 pour que Whoops s'en charge.
 */
class ErrorHandlingMiddleware implements MiddlewareInterface
{
    /**
     * @param ResponseFactoryInterface $responseFactory La fabrique de réponses PSR-17.
     * @param bool $isDebug Flag indiquant si le mode débogage (APP_DEBUG) est actif.
     */
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private bool $isDebug,
    ) {}

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (RouteNotFoundException $e) {
            // Gestion uniforme des 404 (Dev et Prod)
            return $this->generateResponse(404, 'Page Non Trouvée', $e->getMessage());

        } catch (MethodNotAllowedException $e) {
            // Gestion uniforme des 405 (Dev et Prod)
            return $this->generateResponse(405, 'Méthode Non Autorisée', $e->getMessage());

        } catch (\Throwable $e) {
            // STRATÉGIE POUR LES ERREURS 500 CRITIQUES

            if ($this->isDebug) {
                // En DEV : On re-jette l'exception. Elle s'échappe et est capturée par WhoopsMiddleware !
                throw $e;
            }

            // En PROD : Sécurité maximale. On masque le bug et on logue en arrière-plan.
            error_log(sprintf(
                '[%s] Erreur Critique : %s dans %s à la ligne %d',
                date('Y-m-d H:i:s'),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
            ));

            return $this->generateResponse(
                500,
                'Erreur Interne du Serveur',
                'Une erreur imprévue est survenue. Nos équipes techniques ont été alertées.',
            );
        }
    }

    /**
     * Génère une réponse HTTP d'erreur HTML standardisée.
     *
     * @param int $statusCode Code de statut HTTP (404, 405, 500).
     * @param string $title Titre de l'erreur.
     * @param string $message Message de description.
     * @return ResponseInterface
     */
    private function generateResponse(int $statusCode, string $title, string $message): ResponseInterface
    {
        $response = $this->responseFactory->createResponse($statusCode);
        $response = $response->withHeader('Content-Type', 'text/html; charset=utf-8');

        $html = sprintf(
            '<!DOCTYPE html><html><head><title>%s</title></head><body><h1>%d - %s</h1><p>%s</p></body></html>',
            htmlspecialchars($title),
            $statusCode,
            htmlspecialchars($title),
            htmlspecialchars($message),
        );

        $response->getBody()->write($html);

        return $response;
    }
}
