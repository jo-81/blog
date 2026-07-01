<?php

use Framework\Renderer\TwigExtensions\CsrfExtension;
use Framework\Renderer\TwigExtensions\ViteAssetExtension;
use Psr\Container\ContainerInterface;
use Twig\Extension\DebugExtension;

use function DI\autowire;

return [
    'app.twig_extensions' => [
        DebugExtension::class => autowire(DebugExtension::class),
        ViteAssetExtension::class => DI\get(ViteAssetExtension::class),
        CsrfExtension::class => autowire(CsrfExtension::class),
    ],

    ViteAssetExtension::class => function (ContainerInterface $c) {
        return new ViteAssetExtension(
            $_ENV['APP_ENV'] ?? 'dev',
            dirname(__DIR__) . '/public',
            $_ENV['VITE_SERVER_URL'] ?? 'http://localhost:3000'
        );
    },
];