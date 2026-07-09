<?php

declare(strict_types=1);

namespace Framework\Factories;

use Psr\Container\ContainerInterface;
use Framework\Security\CsrfTokenManager;
use Framework\Security\CsrfTokenManagerInterface;

final class CsrfTokenManagerFactory
{
    public function __invoke(ContainerInterface $container): CsrfTokenManagerInterface
    {
        return new CsrfTokenManager();
    }
}
