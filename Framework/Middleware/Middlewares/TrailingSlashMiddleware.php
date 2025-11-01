<?php

declare(strict_types=1);

namespace Framework\Middleware\Middlewares;

use Psr\Http\Message\UriInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Http\Interface\AppRequestInterface;
use Framework\Http\Interface\AppResponseInterface;
use Framework\Http\Interface\ResponseFactoryInterface;

/**
 * Middleware qui gère la redirection des URLs terminant par un slash ('/').
 *
 * Cette classe enlève le slash final des chemins d'URL, sauf si le chemin est la racine
 * ('/') ou correspond à un fichier (déterminé par la présence d'une extension).
 * Elle effectue une redirection permanente (301) vers l'URL sans slash terminal.
 *
 * Exemple :
 * - /example/    => redirige vers /example
 * - /example.php => ne redirige pas
 * - /            => ne redirige pas
 *
 * @package Framework\Middleware\Middlewares
 */
final class TrailingSlashMiddleware implements MiddlewareInterface
{
    /**
     * @param ResponseFactoryInterface $responseFactory Usine pour créer les réponses HTTP.
     */
    public function __construct(private ResponseFactoryInterface $responseFactory)
    {
    }

    /**
     * Traite la requête HTTP, redirige si le chemin se termine par un slash à enlever.
     *
     * @param AppRequestInterface $request Requête HTTP entrante.
     * @param RequestHandlerInterface $handler Prochain handler dans la chaîne de middlewares.
     * @return AppResponseInterface Réponse HTTP, éventuellement une redirection.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): AppResponseInterface
    {
        $uri = $request->getUri();
        $path = $uri->getPath();

        // Ignore les chemins correspondant à un fichier (avec extension)
        if (preg_match('/\.[a-zA-Z0-9]+$/', $path)) {
            /** @var AppResponseInterface */
            return $handler->handle($request);
        }

        // Ignore la racine "/"
        if ('/' === $path) {
            /** @var AppResponseInterface */
            return $handler->handle($request);
        }

        // Redirige si le chemin finit par "/"
        if (str_ends_with($path, '/')) {
            $uri = $uri->withPath(rtrim($path, '/'));

            return $this->responseRedirection($uri);
        }

        /** @var AppResponseInterface */
        return $handler->handle($request);
    }

    /**
     * Crée une réponse HTTP de redirection vers l'URI donné.
     *
     * @param UriInterface $uri URI cible de la redirection.
     * @param int $statusCode Code HTTP de redirection (défaut : 301).
     * @return AppResponseInterface Réponse HTTP de redirection.
     */
    private function responseRedirection(UriInterface $uri, int $statusCode = 301): AppResponseInterface
    {
        return $this->responseFactory->createResponse($statusCode)->withHeader('Location', (string) $uri);
    }
}
