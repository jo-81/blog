<?php

declare(strict_types=1);

namespace Tests;

use App\Entity\User;
use App\Enums\UserRole;
use Nyholm\Psr7\ServerRequest;
use Psr\Container\ContainerInterface;
use Framework\Security\Auth\Authentication;
use Psr\Http\Message\ServerRequestInterface;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class AbstractTestCase extends BaseTestCase
{
    protected ?ContainerInterface $container = null;

    /**
     * Cette méthode s'exécute avant chaque test.
     * Elle permet de réinitialiser le conteneur proprement.
     */
    protected function setUp(): void
    {
        parent::setUp();

        ini_set('session.use_cookies', '0');
        ini_set('session.use_only_cookies', '0');

        $this->container = require __DIR__ . '/../config/container.php';
    }

    /**
     * Raccourci pour récupérer un service du conteneur.
     *
     * @template T
     * @param class-string<T>|string $id
     * @return T
     */
    protected function get(string $id): mixed
    {
        return $this->container->get($id);
    }

    /**
     * Raccourci pour générer rapidement une requête HTTP PSR-7 de test.
     */
    protected function createRequest(string $method, string $uri): ServerRequestInterface
    {
        return new ServerRequest($method, $uri);
    }

    protected function authMock(?UserRole $role = null, ?int $userId = null): void
    {
        $authMock = $this->createMock(Authentication::class);

        if ($role === null && $userId === null) {
            $authMock->method('getUser')->willReturn(null);
        } else {
            $fakeUser = $this->createMock(User::class);

            if (!is_null($role)) {
                $fakeUser->method('getRole')->willReturn($role);
            }

            if (!is_null($userId)) {
                $fakeUser->method('getId')->willReturn($userId);
            }

            $authMock->method('getUser')->willReturn($fakeUser);
        }

        $this->container->set(Authentication::class, $authMock);
    }

    protected function tearDown(): void
    {
        // 1. 🚀 On nettoie en profondeur la session PHP globale
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            session_destroy();
        }

        // On s'assure que le superglobal est bien vide pour le prochain cycle
        $_SESSION = [];

        // 2. On détruit le conteneur comme tu le faisais déjà
        $this->container = null;

        parent::tearDown();
    }
}
