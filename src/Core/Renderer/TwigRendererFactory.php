<?php

namespace Blog\Core\Renderer;

use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigRendererFactory
{
    public function __invoke(ContainerInterface $container): RendererInterface
    {
        /** @var string */
        $viewPath = $container->get('app.templates_directory');
        $loader = new FilesystemLoader($viewPath);
        $twig = new Environment($loader);

        /** @var TwigRenderer */
        $renderer = new TwigRenderer($loader, $twig);
        if ($container->has('app.renderer_extension')) {
            /** @var array<mixed> */
            $extensions = $container->get('app.renderer_extension');
            foreach ($extensions as $extension) {
                $renderer->getTwig()->addExtension($extension); /** @phpstan-ignore-line */
            }
        }

        return $renderer;
    }
}
