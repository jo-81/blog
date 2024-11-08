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

        return new TwigRenderer($loader, $twig);
    }
}
