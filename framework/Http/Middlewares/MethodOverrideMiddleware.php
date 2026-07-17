<?php

declare(strict_types=1);

namespace Framework\Http\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MethodOverrideMiddleware implements MiddlewareInterface
{
    /**
     * Liste des méthodes HTTP autorisées pour la surcharge
     */
    private const ALLOWED_METHODS = ['PUT', 'DELETE', 'PATCH'];

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getMethod() === 'POST') {
            $parsedBody = $request->getParsedBody();

            if (is_array($parsedBody) && isset($parsedBody['_method'])) {
                $method = strtoupper((string) $parsedBody['_method']);

                if (in_array($method, self::ALLOWED_METHODS, true)) {
                    $request = $request->withMethod($method);
                }
            }
        }

        return $handler->handle($request);
    }
}
