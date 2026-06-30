<?php

declare(strict_types=1);

namespace Framework\Security;

use Framework\Session\SessionInterface;

interface CsrfTokenManagerInterface
{
    /**
     * Force la génération d'un NOUVEAU jeton cryptographique et le stocke en session.
     */
    public function generateToken(SessionInterface $session): string;

    /**
     * Récupère le jeton actuel en session. Si aucun jeton n'existe, en génère un.
     */
    public function getToken(SessionInterface $session): string;

    /**
     * Valide le jeton soumis par rapport à celui stocké en session.
     */
    public function validateToken(SessionInterface $session, string $submittedToken): bool;
}
