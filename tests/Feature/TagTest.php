<?php

declare(strict_types=1);

use App\Entity\User;
use App\Enums\UserRole;
use Framework\Http\Exception\UnauthorizedException;
use Framework\Http\HttpPipelineInterface;
use Framework\Http\Kernel;
use Framework\Security\Auth\Authentication;
use Nyholm\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;

test('Exception si un utilisateur non connecté veut accéder à la page des tags', function () {
    // 1. 🚀 On crée un mock natif de la classe Authentication
    $authMock = $this->createMock(Authentication::class);
    
    // On configure le mock pour qu'il renvoie impérativement `null`
    $authMock->method('getUser')->willReturn(null);
    
    // On remplace l'instance dans le conteneur DI de ce test
    $this->container->set(Authentication::class, $authMock);

    $httpPipeline = $this->get(HttpPipelineInterface::class);
    $serverRequest = new ServerRequest('GET', '/admin/tags');

    $kernel = new Kernel($httpPipeline, $serverRequest);
    $kernel->handle();
})->throws(UnauthorizedException::class);

test('Accès à la page des tags si un utisateur est connecté', function () {
    // 2. 🚀 On crée un mock de ton entité User
    $fakeUser = $this->createMock(User::class);
    
    // Si ton entité a une méthode getRole() qui renvoie un objet/enum, on la simule :
    // (Ajuste 'ROLE_ADMIN' ou l'enum selon ton code, ex: Role::ADMIN)
    $fakeUser->method('getRole')->willReturn(UserRole::ADMIN);

    // 3. On crée le mock du service Authentication
    $authMock = $this->createMock(Authentication::class);
    $authMock->method('getUser')->willReturn($fakeUser);
    
    // On injecte le mock dans le conteneur DI
    $this->container->set(Authentication::class, $authMock);

    $httpPipeline = $this->get(HttpPipelineInterface::class);
    $serverRequest = new ServerRequest('GET', '/admin/tags');

    $kernel = new Kernel($httpPipeline, $serverRequest);
    $response = $kernel->handle();

    expect($response)->toBeInstanceOf(ResponseInterface::class)
        ->and($response->getStatusCode())->toBe(200)
        ->and($response->getHeaderLine('Content-Type'))->toContain('text/html');
});