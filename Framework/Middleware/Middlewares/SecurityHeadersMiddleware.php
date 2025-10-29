<?php

declare(strict_types=1);

namespace Framework\Middleware\Middlewares;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Http\Interface\AppResponseInterface;

/**
 * Middleware pour ajouter des en-têtes HTTP de sécurité.
 *
 * Ce middleware insère des en-têtes de sécurité stricts pour protéger l'application
 * contre les attaques courantes telles que le clickjacking, le sniffing de contenu,
 * et les injections XSS. En environnement de production, il applique une politique CSP
 * stricte et active HSTS sur les connexions HTTPS. En développement, les règles CSP sont
 * assouplies pour faciliter les outils de développement.
 *
 * Pour les routes sous "/admin", des en-têtes de cache spécifiques sont appliqués pour
 * garantir que les pages d'administration ne sont pas mises en cache.
 *
 * @package Framework\Middleware\Middlewares
 */
final class SecurityHeadersMiddleware implements MiddlewareInterface
{
    /**
     * Indique si l'environnement est en production.
     *
     * @var bool
     */
    private bool $isProduction;

    /**
     * Initialise le middleware.
     *
     * @param bool $isProduction Active les règles les plus strictes en production.
     */
    public function __construct(bool $isProduction = true)
    {
        $this->isProduction = $isProduction;
    }

    /**
     * Traite la requête HTTP, ajoute les en-têtes de sécurité à la réponse.
     *
     * @param ServerRequestInterface $request Requête HTTP entrante.
     * @param RequestHandlerInterface $handler Prochain handler à appeler.
     * @return AppResponseInterface Réponse HTTP enrichie avec les en-têtes de sécurité.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): AppResponseInterface
    {
        $response = $handler->handle($request);

        $uri = $request->getUri()->getPath();
        $isAdmin = str_starts_with($uri, '/admin');

        // En-têtes de sécurité de base
        $response = $response
            ->withHeader('X-Frame-Options', 'DENY')
            ->withHeader('X-Content-Type-Options', 'nosniff')
            ->withHeader('X-XSS-Protection', '1; mode=block')
            ->withHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->withHeader('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Politique de sécurité du contenu
        $csp = $this->buildCsp($isAdmin);
        $response = $response->withHeader('Content-Security-Policy', $csp);

        // Strict Transport Security (HSTS) pour HTTPS en production
        if ($this->isProduction && $request->getUri()->getScheme() === 'https') {
            $response = $response->withHeader(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        // En-têtes de cache pour l'administration (désactivation du cache)
        if ($isAdmin) {
            $response = $response
                ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, private')
                ->withHeader('Pragma', 'no-cache')
                ->withHeader('Expires', '0');
        }

        // En développement, indique l'environnement via un header spécifique
        if (!$this->isProduction) {
            $response = $response->withHeader('X-Environment', 'development');
        }

        /** @var AppResponseInterface */
        return $response;
    }

    /**
     * Construit la directive Content-Security-Policy (CSP) selon le contexte.
     *
     * @param bool $isAdmin True si la route est une route d'administration.
     * @return string Directive CSP à insérer dans l'en-tête HTTP.
     */
    private function buildCsp(bool $isAdmin): string
    {
        $directives = [
            "default-src 'self'",
            "font-src 'self'",
            "connect-src 'self'",
            "frame-ancestors 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ];

        if ($this->isProduction) {
            // En production, la politique est stricte : pas de scripts/styles inline
            $directives[] = "script-src 'self'";
            $directives[] = "style-src 'self'";
        } else {
            // En développement, on autorise unsafe-inline et unsafe-eval pour outils dev
            $directives[] = "script-src 'self' 'unsafe-inline' 'unsafe-eval'";
            $directives[] = "style-src 'self' 'unsafe-inline'";
        }

        // Images autorisées depuis le même domaine, data URI et HTTPS
        $directives[] = "img-src 'self' data: https:";

        if ($isAdmin) {
            // Restrictions renforcées pour pages admin
            $directives[] = "object-src 'none'";
            $directives[] = "media-src 'none'";
        }

        return implode('; ', $directives);
    }
}
