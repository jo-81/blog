<?php

namespace Framework\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface HttpPipelineInterface
{
    /**
     * Traite la requête à travers la pile de composants et renvoie la réponse.
     */
    public function process(ServerRequestInterface $request): ResponseInterface;
}