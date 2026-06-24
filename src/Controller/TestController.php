<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class TestController
{
    public function __construct(private ResponseFactoryInterface $responseFactory) {}

    public function index(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse(200);
        $response->getBody()->write('<h1>Homepage</h1>');

        return $response;
    }

    public function postsList(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse(200);
        $response->getBody()->write('<h1>Liste des cours</h1>');

        return $response;
    }
}
