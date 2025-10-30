<?php

use Framework\Router\RouterFactory;
use Framework\Renderer\RendererFactory;
use Framework\Renderer\Twig\TwigRendererFactory;
use Framework\Router\AltoRouter\AltoRouterFactory;
use Framework\Http\Request\Guzzle\GuzzleRequestFactory;
use Framework\Http\Response\Guzzle\GuzzleResponseFactory;
use Framework\Session\NativeSessionFactory;

return [
    // Renderer
    "app.renderer_interface" => 
        DI\factory(RendererFactory::class)->parameter('rendererFactory', DI\get(TwigRendererFactory::class)),

    TwigRendererFactory::class => DI\autowire(),

    "app.renderer_paths" => [dirname(__DIR__) . "/templates"],

    "app.renderer_options" => [
        'cache' => $_ENV["APP_ENV"] == "dev" ? false : dirname(__DIR__) . '/var/cache/renderer',
        'debug' => $_ENV["APP_ENV"] !== "prod",
        'auto_reload' => $_ENV["APP_ENV"] !== "prod",
        'strict_variables' => true,
    ],

    "app.renderer_extensions" => [],

    // Http
    "app.request_interface" => DI\get(GuzzleRequestFactory::class),
    GuzzleRequestFactory::class => DI\autowire(),

    "app.response_interface" => DI\get(GuzzleResponseFactory::class),
    GuzzleResponseFactory::class => DI\autowire(),

    // Router
    "app.router_interface" =>
        DI\factory(RouterFactory::class)->parameter('routerFactory', DI\get(AltoRouterFactory::class)),
        
    AltoRouterFactory::class => DI\autowire(),
    "app.routes" => [],

    // Session
    "app.session_interface" => DI\factory(NativeSessionFactory::class),
    NativeSessionFactory::class => DI\autowire(),
    "app.session_options" => [
        'dev' => [
            'name' => 'DEV_SESSION',
            'lifetime' => 86400,
            'secure' => false,
            'httponly' => true,
            'samesite' => 'Lax',
        ],
        'prod' => [
            'name' => 'APP_SESSION',
            'lifetime' => 3600,
            'path' => '/',
            'domain' => '.example.com',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict',
        ]
    ]
];