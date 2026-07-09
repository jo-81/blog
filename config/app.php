<?php

use Framework\Renderer\TwigExtensions\CsrfExtension;
use Framework\Renderer\TwigExtensions\FormTypeExtension;
use Framework\Renderer\TwigExtensions\MessageFlashExtension;
use Framework\Renderer\TwigExtensions\ViteAssetExtension;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

use function DI\autowire;

return [
    Environment::class => function (ContainerInterface $c) {
        if (! $c->has('settings.template_directory')) {
            throw new \RuntimeException("La clé settings.template_directory n'existe pas dans le container.");
        }

        $templatesDir = $c->get('settings.template_directory');
        if (!file_exists($templatesDir)) {
            throw new \RuntimeException(sprintf("Le dossier templates : '%s' n'existe pas.", $templatesDir));
        }

        $loader = new FilesystemLoader($templatesDir);
        $environment = new Environment($loader, [
            'debug' => $c->has('settings.debug') ? $c->get('settings.debug') : true,
        ]);

        return $environment;
    },

    'app.twig_widget' => "widgets/form",

    'app.twig_extensions' => [
        DebugExtension::class => autowire(DebugExtension::class),
        ViteAssetExtension::class => DI\get(ViteAssetExtension::class),
        CsrfExtension::class => autowire(CsrfExtension::class),
        MessageFlashExtension::class => autowire(MessageFlashExtension::class),
        FormTypeExtension::class => DI\get(FormTypeExtension::class),
    ],

    FormTypeExtension::class => function(ContainerInterface $c) {
        if (!$c->has('app.twig_widget')) {
            throw new \RuntimeException("La clé 'app.twig_widget' n'est pas définit.");
        }

        $directory = $c->get('app.twig_widget');
        return new FormTypeExtension($c->get(Environment::class), $directory);
    },

    ViteAssetExtension::class => function (ContainerInterface $c) {
        return new ViteAssetExtension(
            $_ENV['APP_ENV'] ?? 'dev',
            dirname(__DIR__) . '/public',
            $_ENV['VITE_SERVER_URL'] ?? 'http://localhost:3000'
        );
    },
];