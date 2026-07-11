<?php

declare(strict_types=1);

namespace Framework\Http\Middlewares;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Framework\Security\Auth\Authentication;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Http\Exception\ForbiddenException;
use Framework\Http\Exception\UnauthorizedException;

class AuthentificationMiddleware implements MiddlewareInterface
{
    public function __construct(private Authentication $auth, private ContainerInterface $container) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $accessRoutes = [];
        // Si la clé access_control exist
        if ($this->container->has('access_control')) {
            if (!is_array($this->container->get('access_control'))) {
                throw new \RuntimeException("La clé 'access_control' doit retourner un array.");
            }

            $accessRoutes = $this->container->get('access_control');
        }

        if (empty($accessRoutes)) {
            return $handler->handle($request);
        }

        $user = $this->auth->getUser();
        foreach ($accessRoutes as $path => $roles) {
            if (preg_match('#' . $path . '#', $request->getUri()->getPath())) {
                if (is_null($user)) {
                    throw new UnauthorizedException();
                }

                if (! in_array($user->getRole()->value, $roles, true)) {
                    throw new ForbiddenException();
                }
            }
        }

        return $handler->handle($request);
    }
}
