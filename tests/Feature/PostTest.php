<?php

declare(strict_types=1);

use App\Enums\UserRole;
use Framework\Http\Kernel;
use Nyholm\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Framework\Http\HttpPipelineInterface;
use Framework\Http\Exception\UnauthorizedException;

test('Accès à la page des posts si un utisateur est connecté', function (string $path) {
    $this->authMock(UserRole::ADMIN);

    $httpPipeline = $this->get(HttpPipelineInterface::class);
    $serverRequest = new ServerRequest('GET', $path);

    $kernel = new Kernel($httpPipeline, $serverRequest);
    $response = $kernel->handle();

    expect($response)->toBeInstanceOf(ResponseInterface::class)
        ->and($response->getStatusCode())->toBe(200)
        ->and($response->getHeaderLine('Content-Type'))->toContain('text/html')
    ;
})->with(['/admin/posts']);

test('Exception si un utilisateur non connecté veut accéder à la page des posts', function (string $path) {
    $httpPipeline = $this->get(HttpPipelineInterface::class);
    $serverRequest = new ServerRequest('GET', $path);

    $kernel = new Kernel($httpPipeline, $serverRequest);
    $response = $kernel->handle();

    expect($response)->toBeInstanceOf(ResponseInterface::class)
        ->and($response->getStatusCode())->toBe(200)
        ->and($response->getHeaderLine('Content-Type'))->toContain('text/html')
    ;
})->with(['/admin/posts'])->throws(UnauthorizedException::class);;
