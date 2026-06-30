<?php

declare(strict_types=1);

namespace Framework\Http\Middlewares;

use Framework\Exceptions\CsrfException;
use Framework\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Security\CsrfTokenManagerInterface;

class CsrfMiddleware implements MiddlewareInterface
{
    private const FORM_FIELD = '_csrf';

    public function __construct(private CsrfTokenManagerInterface $tokenManager) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var SessionInterface|null $session */
        $session = $request->getAttribute('session');

        if (!$session instanceof SessionInterface) {
            throw new \RuntimeException('Le CsrfMiddleware nécessite le SessionMiddleware en amont.');
        }

        // 1. On délègue la récupération/génération au service
        $token = $this->tokenManager->getToken($session);

        // 2. On injecte le token dans la requête pour les vues HTML
        $request = $request->withAttribute('csrf_token', $token);

        // 3. Si c'est une requête de modification, on valide via le service
        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            $parsedBody = $request->getParsedBody();
            $submittedToken = is_array($parsedBody) ? ($parsedBody[self::FORM_FIELD] ?? '') : '';

            // Le middleware pose simplement la question au manager : "Est-ce valide ?"
            if (empty($submittedToken) || !$this->tokenManager->validateToken($session, (string) $submittedToken)) {
                throw new CsrfException('Erreur CSRF : Jeton de sécurité invalide ou expiré.');
            }
        }

        return $handler->handle($request);
    }
}
