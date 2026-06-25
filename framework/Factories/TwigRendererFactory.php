<?php

declare(strict_types=1);

namespace Framework\Factories;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Framework\Adapters\TwigRenderer;
use Psr\Container\ContainerInterface;
use Twig\Extension\ExtensionInterface;
use Framework\Renderer\RendererInterface;

class TwigRendererFactory
{
    public function __invoke(ContainerInterface $container): RendererInterface
    {
        if (! $container->has('settings.template_directory')) {
            throw new \RuntimeException("La clé settings.template_directory n'existe pas dans le container.");
        }

        $templatesDir = $container->get('settings.template_directory');
        if (!file_exists($templatesDir)) {
            throw new \RuntimeException(sprintf("Le dossier templates : '%s' n'existe pas.", $templatesDir));
        }

        $loader = new FilesystemLoader($templatesDir);
        $environment = new Environment($loader, [
            'debug' => $container->has('settings.debug') ? $container->get('settings.debug') : true,
        ]);

        $twigRenderer = new TwigRenderer($environment);

        // Ajout des extensions si définit
        if ($container->has('app.twig_extensions')) {
            $twigExtensions = $container->get('app.twig_extensions');

            if (is_array($twigExtensions)) {
                foreach ($twigExtensions as $extension) {
                    if ($extension instanceof ExtensionInterface) {
                        $twigRenderer->addExtension($extension);
                    }
                }
            }
        }

        // Ajout des variables global s'il définit
        if ($container->has('app.twig_globals')) {
            $twigGlobals = $container->get('app.twig_globals');

            if (is_array($twigGlobals)) {
                foreach ($twigGlobals as $global => $value) {
                    $twigRenderer->addGlobal((string) $global, $value);
                }
            }
        }

        return $twigRenderer;
    }
}
