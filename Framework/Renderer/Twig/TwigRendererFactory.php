<?php

declare(strict_types=1);

namespace Framework\Renderer\Twig;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Framework\Renderer\Interface\RendererInterface;
use Framework\Renderer\Interface\RendererFactoryInterface;

class TwigRendererFactory implements RendererFactoryInterface
{
    public function factory(array $paths, array $options, array $extensions): RendererInterface
    {
        $environment = new Environment(new FilesystemLoader($paths), $options);
        $twig = new TwigRenderer($environment);
        $twig->loadExtensions($extensions);

        return $twig;
    }
}
