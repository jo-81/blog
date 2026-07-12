<?php

declare(strict_types=1);

namespace Framework\Renderer\TwigExtensions;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Framework\Security\Auth\Authentication;

final class UserExtension extends AbstractExtension
{
    public function __construct(private Authentication $auth) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_user', [$this, 'doGetUser']),
        ];
    }

    public function doGetUser(?string $property = null): mixed
    {
        $user = $this->auth->getUser();
        if (is_null($property)) {
            return $user;
        }

        $method = 'get' . ucfirst($property);
        if (method_exists($user, $method)) {
            return $user->$method();
        }

        throw new \RuntimeException(sprintf("La propriété %s n'existe pas pour User", $property));
    }
}
