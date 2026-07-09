<?php

declare(strict_types=1);

use Framework\Http\Kernel;
use Nyholm\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Framework\Http\HttpPipelineInterface;

test('la route de connexion retourne une reponse html valide avec un code 200', function () {
    $httpPipeline = $this->get(HttpPipelineInterface::class);
    $serverRequest = new ServerRequest('GET', '/connexion');

    $kernel = new Kernel($httpPipeline, $serverRequest);

    $response = $kernel->handle();

    expect($response)->toBeInstanceOf(ResponseInterface::class)
        ->and($response->getStatusCode())->toBe(200)
        ->and($response->getHeaderLine('Content-Type'))->toContain('text/html');
});
