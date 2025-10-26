<?php

declare(strict_types=1);

namespace Framework\Trait;

use Psr\Container\ContainerInterface;

trait ContainerTrait
{
    public function get(ContainerInterface $container, string $name, mixed $default): mixed
    {
        if ($container->has($name)) {
            return $container->get($name);
        }

        return $default;
    }
}
