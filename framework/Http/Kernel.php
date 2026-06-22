<?php

declare(strict_types=1);

namespace Framework\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Cœur du Framework.
 * * Responsable de la capture de la requête HTTP globale et de son acheminement
 * à travers la pile de middlewares pour produire une réponse HTTP.
 */
class Kernel
{
    public function __construct(private HttpPipelineInterface $pipeline, private ServerRequestInterface $request) {}

    /**
     * Gère la requête HTTP entrante et retourne la réponse générée par la pile de middlewares.
     *
     * @return ResponseInterface La réponse HTTP à renvoyer au navigateur.
     */
    public function handle(): ResponseInterface
    {
        return $this->pipeline->process($this->request);
    }
}
