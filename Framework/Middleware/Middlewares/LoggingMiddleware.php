<?php

declare(strict_types=1);

namespace Framework\Middleware\Middlewares;

use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Http\Interface\AppResponseInterface;

/**
 * Middleware de journalisation des requêtes HTTP et des réponses.
 *
 * Enregistre dans le logger la méthode, l'URI, le code de statut, la durée de traitement
 * ainsi que l'adresse IP du client. En cas d'exception, celle-ci est également loggée
 * avec tous ses détails.
 *
 * @package Framework\Middleware\Middlewares
 */
final class LoggingMiddleware implements MiddlewareInterface
{
    /**
     * @param LoggerInterface $logger Système de journalisation PSR-3 utilisé pour écrire les logs.
     */
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * Intercepte la requête, mesure la durée d'exécution et journalise le résultat ou l'exception.
     *
     * @param ServerRequestInterface $request La requête HTTP entrante.
     * @param RequestHandlerInterface $handler Le handler suivant dans la chaîne de middlewares.
     * @return AppResponseInterface La réponse renvoyée.
     * @throws \Throwable Toute exception sera propagée après journalisation.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): AppResponseInterface
    {
        $startTime = microtime(true);

        try {
            /** @var AppResponseInterface */
            $response = $handler->handle($request);
            $this->logResponse($request, $response, $startTime);
            return $response;

        } catch (\Throwable $e) {
            $this->logException($request, $e, $startTime);
            throw $e;
        }
    }

    /**
     * Journalise la réponse HTTP, incluant la méthode, l'URI, le statut et la durée.
     *
     * @param ServerRequestInterface $request La requête traitée.
     * @param AppResponseInterface $response La réponse générée.
     * @param float $startTime Timestamp du début du traitement.
     */
    private function logResponse(
        ServerRequestInterface $request,
        AppResponseInterface $response,
        float $startTime
    ): void {
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        $statusCode = $response->getStatusCode();
        $logLevel = $this->getLogLevel($statusCode);

        $this->logger->log(
            $logLevel,
            sprintf(
                '%s %s - %d (%s ms)',
                $request->getMethod(),
                $request->getUri()->getPath(),
                $statusCode,
                $duration
            ),
            [
                'method'      => $request->getMethod(),
                'uri'         => (string) $request->getUri(),
                'status'      => $statusCode,
                'duration_ms' => $duration,
                'ip'          => $this->getClientIp($request),
            ]
        );
    }

    /**
     * Journalise une exception survenue lors du traitement de la requête.
     *
     * @param ServerRequestInterface $request La requête en cause.
     * @param \Throwable $e L'exception capturée.
     * @param float $startTime Timestamp du début du traitement.
     */
    private function logException(
        ServerRequestInterface $request,
        \Throwable $e,
        float $startTime
    ): void {
        $duration = round((microtime(true) - $startTime) * 1000, 2);

        $this->logger->error(
            sprintf(
                '%s %s - Exception: %s',
                $request->getMethod(),
                $request->getUri()->getPath(),
                $e->getMessage()
            ),
            [
                'method'      => $request->getMethod(),
                'uri'         => (string) $request->getUri(),
                'duration_ms' => $duration,
                'exception'   => get_class($e),
                'message'     => $e->getMessage(),
                'file'        => $e->getFile(),
                'line'        => $e->getLine(),
            ]
        );
    }

    /**
     * Détermine le niveau de log à utiliser selon le code de retour HTTP.
     *
     * @param int $statusCode Le code de statut HTTP de la réponse.
     * @return string Un des niveaux PSR-3 : LogLevel::INFO, ::WARNING ou ::ERROR.
     */
    private function getLogLevel(int $statusCode): string
    {
        return match (true) {
            $statusCode >= 500 => LogLevel::ERROR,
            $statusCode >= 400 => LogLevel::WARNING,
            default            => LogLevel::INFO,
        };
    }

    /**
     * Extrait l'adresse IP du client à partir des headers ou des paramètres serveur.
     *
     * @param ServerRequestInterface $request Requête HTTP reçue.
     * @return string Adresse IP du client ou 'unknown'.
     */
    private function getClientIp(ServerRequestInterface $request): string
    {
        if ($request->hasHeader('X-Forwarded-For')) {
            $ips = explode(',', $request->getHeaderLine('X-Forwarded-For'));
            return trim($ips[0]);
        }

        if ($request->hasHeader('X-Real-IP')) {
            return $request->getHeaderLine('X-Real-IP');
        }

        $serverParams = $request->getServerParams();
        return $serverParams['REMOTE_ADDR'] ?? 'unknown';
    }
}
