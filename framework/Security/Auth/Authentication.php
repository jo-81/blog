<?php

declare(strict_types=1);

namespace Framework\Security\Auth;

use Psr\Log\LoggerInterface;
use Framework\Session\SessionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Database\UserRepositoryInterface;

class Authentication
{
    private const USER_SESSION_KEY = '_auth_user_id';

    public function __construct(
        private SessionInterface $session,
        private UserRepositoryInterface $userRepository,
        private LoggerInterface $logger,
        private ServerRequestInterface $request,
    ) {}

    /**
     * Tente de connecter un utilisateur avec ses identifiants
     */
    public function attempt(string $email, string $password): bool
    {
        $serverParams = $this->request->getServerParams();
        $ip = $serverParams['REMOTE_ADDR'] ?? '127.0.0.1';

        // 1. Chercher l'utilisateur par son email
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            $this->logger->warning('Tentative de connexion échouée : Utilisateur inconnu.', [
                'email' => $email,
                'ip'    => $ip,
            ]);

            return false;
        }

        // 2. Vérifier le mot de passe (en utilisant le hachage sécurisé de PHP)
        if (!password_verify($password, $user->getPassword())) {
            $this->logger->warning('Tentative de connexion échouée : Mot de passe incorrect.', [
                'email'   => $email,
                'user_id' => $user->getId(),
                'ip'      => $ip,
            ]);

            return false;
        }

        // 3. Si tout est OK, on enregistre l'identifiant en session
        $this->session->set(self::USER_SESSION_KEY, $user->getId());

        return true;
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout(): void
    {
        $this->session->remove(self::USER_SESSION_KEY);
    }

    /**
     * Vérifie si un utilisateur est actuellement connecté
     */
    public function check(): bool
    {
        return $this->session->has(self::USER_SESSION_KEY);
    }
}
