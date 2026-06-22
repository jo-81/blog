<?php

declare(strict_types=1);

namespace Framework\Http;

use Psr\Http\Message\ResponseInterface;

/**
 * Émetteur de réponse HTTP.
 * Se charge de traduire une réponse PSR-7 en en-têtes et corps de texte natifs PHP.
 */
class ResponseEmitter
{
    /**
     * Émet la réponse HTTP au navigateur.
     *
     * @param ResponseInterface $response La réponse PSR-7 à envoyer.
     * @return void
     */
    public function emit(ResponseInterface $response): void
    {
        // 1. Envoi du code le statut HTTP
        http_response_code($response->getStatusCode());

        // 2. Envoi des en-têtes (Headers)
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                // Le paramètre 'false' évite d'écraser les en-têtes de même nom (ex: Set-Cookie)
                header(sprintf('%s: %s', $name, $value), false);
            }
        }

        // 3. Envoi du corps de la réponse
        echo $response->getBody();
    }
}
