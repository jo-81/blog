<?php

// RequestFactory.php

declare(strict_types=1);

namespace Framework\Http\Request;

use Psr\Container\ContainerInterface;
use Framework\Http\Interface\AppRequestInterface;
use Framework\Http\Interface\RequestFactoryInterface;

class RequestFactory
{
    public function __invoke(
        ContainerInterface $container,
        RequestFactoryInterface $requestFactory
    ): AppRequestInterface {
        return $requestFactory->createFromGlobals();
    }
}
