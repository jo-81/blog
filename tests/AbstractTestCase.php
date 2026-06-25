<?php

declare(strict_types=1);

namespace Tests;

use Nyholm\Psr7\ServerRequest;
use Psr\Container\ContainerInterface;
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
        $this->container = null;
        parent::tearDown();
    }
}
