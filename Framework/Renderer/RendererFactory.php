<?php

declare(strict_types=1);

namespace Framework\Renderer;

use Psr\Container\ContainerInterface;
use Framework\Renderer\Interface\RendererInterface;
use Framework\Renderer\Interface\RendererFactoryInterface;

class RendererFactory
{
    public function __invoke(
        ContainerInterface $container,
        RendererFactoryInterface $rendererFactory
    ): RendererInterface {
        $paths = $this->get($container, 'app.renderer_paths', []);
        $options = $this->get($container, 'app.renderer_options', []);
        $extensions = $this->get($container, 'app.renderer_extension', []);

        return $rendererFactory->factory($paths, $options, $extensions);
    }

    private function get(ContainerInterface $container, string $name, mixed $default): mixed
    {
        if ($container->has($name)) {
            return $container->get($name);
        }

        return $default;
    }
}
