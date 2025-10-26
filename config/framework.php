<?php

use Framework\Renderer\RendererFactory;
use Framework\Renderer\Twig\TwigRendererFactory;

return [
    // Renderer
    "app.renderer_interface" => 
        DI\factory(RendererFactory::class)->parameter('rendererFactory', DI\get(TwigRendererFactory::class)),

    TwigRendererFactory::class => DI\autowire(TwigRendererFactory::class),

    "app.renderer_paths" => [dirname(__DIR__) . "/templates"],

    "app.renderer_options" => [
        'cache' => $_ENV["APP_ENV"] == "dev" ? false : dirname(__DIR__) . '/var/cache/renderer',
        'debug' => $_ENV["APP_ENV"] !== "prod",
        'auto_reload' => $_ENV["APP_ENV"] !== "prod",
        'strict_variables' => true,
    ],

    "app.renderer_extensions" => [],
];