<?php

declare(strict_types=1);

namespace Framework\Security;

use Framework\Session\SessionInterface;

class CsrfTokenManager implements CsrfTokenManagerInterface
{
    private const SESSION_KEY = '_csrf_token';

    public function generateToken(SessionInterface $session): string
    {
        // Génération cryptographique isolée
        $token = bin2hex(random_bytes(32));
        $session->set(self::SESSION_KEY, $token);

        return $token;
    }

    public function getToken(SessionInterface $session): string
    {
        if (!$session->has(self::SESSION_KEY)) {
            // Si absent, on délègue à la méthode de génération
            return $this->generateToken($session);
        }

        return (string) $session->get(self::SESSION_KEY);
    }

    public function validateToken(SessionInterface $session, string $submittedToken): bool
    {
        if (!$session->has(self::SESSION_KEY)) {
            return false;
        }

        $storedToken = (string) $session->get(self::SESSION_KEY);

        return hash_equals($storedToken, $submittedToken);
    }
}
