<?php

declare(strict_types=1);

namespace Tests;

use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

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
