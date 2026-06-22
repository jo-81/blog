<?php

declare(strict_types=1);

namespace Framework\Adapters;

use Relay\Relay;
use Psr\Http\Message\ResponseInterface;
use Framework\Http\HttpPipelineInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Adaptateur pour le pipeline HTTP de Relay.
 *
 * Cette classe fait le pont entre l'interface contractuelle du framework
 * et l'implémentation concrète du package tiers Relay.
 */
class RelayHttpPipeline implements HttpPipelineInterface
{
    /**
     * @param Relay $pipeline L'instance concrète du moteur de pipeline Relay.
     */
    public function __construct(private Relay $pipeline) {}

    /**
     * Traite la requête HTTP entrante à travers la pile de middlewares de Relay.
     *
     * @param ServerRequestInterface $request La requête HTTP PSR-7 entrante.
     * @return ResponseInterface La réponse HTTP PSR-7 générée par la pile.
     */
    public function process(ServerRequestInterface $request): ResponseInterface
    {
        return $this->pipeline->handle($request);
    }
}
