<?php

declare(strict_types=1);

namespace Framework\Middleware\Middlewares;

use Psr\Log\LoggerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Http\Interface\AppResponseInterface;
use Framework\Renderer\Interface\RendererInterface;
use Framework\Http\Interface\ResponseFactoryInterface;

/**
 * Middleware de gestion centralisée des erreurs et exceptions HTTP.
 *
 * Intercepte toutes les exceptions non gérées dans la pile de middlewares.
 * Génère une réponse HTML adaptée selon l'environnement (débogage ou production),
 * et journalise l'erreur en utilisant le Logger fourni.
 *
 * @package Framework\Middleware\Middlewares
 */
final class ErrorHandlerMiddleware implements MiddlewareInterface
{
    /**
     * @param RendererInterface $renderer Système de rendu des templates HTML.
     * @param ResponseFactoryInterface $responseFactory Générateur de réponses HTTP.
     * @param LoggerInterface $logger Logger PSR-3 pour journaliser les erreurs et exceptions.
     * @param bool $debug Active le mode debug détaillé (affichage stack trace et source).
     */
    public function __construct(
        private RendererInterface $renderer,
        private ResponseFactoryInterface $responseFactory,
        private LoggerInterface $logger,
        private bool $debug = false,
    ) {
    }

    /**
     * Intercepte et gère les exceptions ; passe la requête au prochain handler en mode normal.
     *
     * @param ServerRequestInterface $request Requête HTTP courante.
     * @param RequestHandlerInterface $handler Handler suivant dans la chaîne des middlewares.
     * @return AppResponseInterface Réponse HTTP (normale ou erreur).
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): AppResponseInterface
    {
        try {
            /** @var AppResponseInterface */
            return $handler->handle($request);

        } catch (\Throwable $e) {
            return $this->handleException($e, $request);
        }
    }

    /**
     * Détermine et génère la réponse appropriée en cas d'exception, avec journalisation.
     *
     * @param \Throwable $e Exception à gérer.
     * @param ServerRequestInterface $request Requête HTTP concernée.
     * @return AppResponseInterface Réponse d'erreur générée.
     */
    private function handleException(\Throwable $e, ServerRequestInterface $request): AppResponseInterface
    {
        $statusCode = $this->getHttpStatusCode($e);
        $this->logException($e, $request, $statusCode);

        return $this->createErrorResponse($e, $request, $statusCode);
    }

    /**
     * Génère la réponse HTTP d'erreur (délègue à createHtmlErrorResponse).
     *
     * @param \Throwable $e Exception découlant d'une erreur.
     * @param ServerRequestInterface $request Requête HTTP concernée.
     * @param int $statusCode Code HTTP à renvoyer.
     * @return AppResponseInterface Réponse d'erreur HTML.
     */
    private function createErrorResponse(\Throwable $e, ServerRequestInterface $request, int $statusCode): AppResponseInterface
    {
        return $this->createHtmlErrorResponse($e, $statusCode);
    }

    /**
     * Crée la réponse HTML qui sera affichée (page d'erreur production ou debug).
     *
     * @param \Throwable $e Exception rencontrée.
     * @param int $statusCode Code HTTP d'erreur.
     * @return AppResponseInterface Réponse contenant la page HTML d'erreur.
     */
    private function createHtmlErrorResponse(\Throwable $e, int $statusCode): AppResponseInterface
    {
        if ($this->debug) {
            $html = $this->renderDebugPage($e, $statusCode);
        } else {
            $html = $this->renderProductionErrorPage($statusCode);
        }

        $response = $this->responseFactory->createHtmlResponse($html, $statusCode);
        $response->getBody()->write($html);

        return $response
            ->withStatus($statusCode)
            ->withHeader('Content-Type', 'text/html; charset=utf-8');
    }

    /**
     * Récupère le code HTTP à partir de l'exception rencontrée.
     *
     * Pour certaines exceptions courantes, un mapping personnalisé est utilisé.
     *
     * @param \Throwable $e Exception à analyser.
     * @return int Code HTTP correspondant à l'exception.
     */
    private function getHttpStatusCode(\Throwable $e): int
    {
        // Si l'exception a un code HTTP valide
        $code = $e->getCode();
        if ($code >= 400 && $code < 600) {
            return $code;
        }

        $exceptionMap = [
            'NotFoundException'      => 404,
            'UnauthorizedException'  => 401,
            'ForbiddenException'     => 403,
            'ValidationException'    => 422,
            'MethodNotAllowedException' => 405,
            'TooManyRequestsException'  => 429,
        ];

        $className = basename(str_replace('\\', '/', get_class($e)));

        return $exceptionMap[$className] ?? 500;
    }

    /**
     * Journalise l'exception via le Logger, avec le niveau adapté au code HTTP.
     *
     * @param \Throwable $e Exception à journaliser.
     * @param ServerRequestInterface $request Requête liée à l'erreur.
     * @param int $statusCode Code HTTP retenu.
     */
    private function logException(\Throwable $e, ServerRequestInterface $request, int $statusCode): void
    {
        $context = [
            'exception'  => get_class($e),
            'message'    => $e->getMessage(),
            'file'       => $e->getFile(),
            'line'       => $e->getLine(),
            'code'       => $e->getCode(),
            'trace'      => $e->getTraceAsString(),
            'url'        => (string) $request->getUri(),
            'method'     => $request->getMethod(),
            'ip'         => $this->getClientIp($request),
            'user_agent' => $request->getHeaderLine('User-Agent'),
        ];

        if ($statusCode >= 500) {
            $this->logger->error($e->getMessage(), $context);
        } elseif ($statusCode >= 400) {
            $this->logger->warning($e->getMessage(), $context);
        } else {
            $this->logger->info($e->getMessage(), $context);
        }
    }

    /**
     * Détecte l'adresse IP du client via les headers HTTP ou paramètres serveur.
     *
     * @param ServerRequestInterface $request Requête provenant du client.
     * @return string Adresse IP détectée ou 'unknown'.
     */
    private function getClientIp(ServerRequestInterface $request): string
    {
        $serverParams = $request->getServerParams();

        if ($request->hasHeader('X-Forwarded-For')) {
            $ips = explode(',', $request->getHeaderLine('X-Forwarded-For'));
            return trim($ips[0]);
        }

        if ($request->hasHeader('X-Real-IP')) {
            return $request->getHeaderLine('X-Real-IP');
        }

        return $serverParams['REMOTE_ADDR'] ?? 'unknown';
    }

    /**
     * Produit la page de debug détaillée à partir de l'exception.
     *
     * @param \Throwable $e Exception levée.
     * @param int $statusCode Code HTTP d'erreur.
     * @return string Contenu HTML pour le rendu debug.
     */
    private function renderDebugPage(\Throwable $e, int $statusCode): string
    {
        $exceptionClass = htmlspecialchars(get_class($e));
        $message        = htmlspecialchars($e->getMessage());
        $file           = htmlspecialchars($e->getFile());
        $line           = $e->getLine();
        $trace          = htmlspecialchars($e->getTraceAsString());

        $sourceCode = $this->getSourceCodeContext($e->getFile(), $e->getLine());

        return $this->renderer->render($this->getTemplate($statusCode), [
            'exceptionClass' => $exceptionClass,
            'message'        => $message,
            'file'           => $file,
            'line'           => $line,
            'trace'          => $trace,
            'sourceCode'     => $sourceCode,
            'statusCode'     => $statusCode,
        ]);
    }

    /**
     * Extrait le code source autour de la ligne de l'erreur pour l'affichage debug.
     *
     * @param string $file Chemin du fichier source.
     * @param int $errorLine Ligne concernée par l'exception.
     * @param int $context Nombre de lignes autour de l'erreur à afficher.
     * @return string HTML formaté du contexte source.
     */
    private function getSourceCodeContext(string $file, int $errorLine, int $context = 10): string
    {
        if (!is_readable($file)) {
            return '';
        }

        $lines = file($file);
        $start = max(0, $errorLine - $context - 1);
        $end   = min(count($lines), $errorLine + $context);

        $html = '<div class="section"><h2>Source Code</h2><div class="code-block">';

        for ($i = $start; $i < $end; $i++) {
            $lineNum     = $i + 1;
            $lineContent = htmlspecialchars($lines[$i]);
            $isErrorLine = $lineNum === $errorLine;
            $class       = $isErrorLine ? ' error-line' : '';

            $html .= sprintf(
                '<div class="%s"><span class="line-number">%d</span>%s</div>',
                $class,
                $lineNum,
                $lineContent
            );
        }

        $html .= '</div></div>';

        return $html;
    }

    /**
     * Génère la page d'erreur standard affichée en production.
     *
     * @param int $statusCode Code HTTP de la réponse.
     * @return string HTML rendu de la page d'erreur.
     */
    private function renderProductionErrorPage(int $statusCode): string
    {
        $titles = [
            400 => 'Requête invalide',
            401 => 'Authentification requise',
            403 => 'Accès interdit',
            404 => 'Page introuvable',
            405 => 'Méthode non autorisée',
            429 => 'Trop de requêtes',
            500 => 'Erreur serveur',
            503 => 'Service indisponible',
        ];

        $messages = [
            400 => 'La requête que vous avez envoyée est invalide.',
            401 => 'Vous devez vous connecter pour accéder à cette page.',
            403 => 'Vous n\'avez pas les permissions pour accéder à cette page.',
            404 => 'La page que vous recherchez n\'existe pas ou a été déplacée.',
            405 => 'Cette méthode HTTP n\'est pas autorisée pour cette ressource.',
            429 => 'Vous avez effectué trop de requêtes. Veuillez réessayer plus tard.',
            500 => 'Une erreur interne s\'est produite. Nos équipes ont été notifiées.',
            503 => 'Le service est temporairement indisponible. Veuillez réessayer plus tard.',
        ];

        $title   = $titles[$statusCode] ?? 'Erreur';
        $message = $messages[$statusCode] ?? 'Une erreur s\'est produite.';

        return $this->renderer->render($this->getTemplate($statusCode), [
            'title'      => $title,
            'message'    => $message,
            'statusCode' => $statusCode,
        ]);
    }

    /**
     * Retourne le template selon le statusCode
     *
     * @param  int $statusCode
     */
    private function getTemplate(int $statusCode): string
    {
        if ($this->debug) {
            return "error/debug.html.twig";
        }

        $template = "error/{$statusCode}.html.twig";
        if ($this->renderer->isTemplateExists($template)) {
            return $template;
        }

        return "error/500.html.twig";
    }
}
