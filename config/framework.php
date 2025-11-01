<?php

use Psr\Log\LoggerInterface;
use Framework\Router\RouterFactory;
use Framework\Renderer\RendererFactory;
use Framework\Http\Request\RequestFactory;
use Framework\Session\NativeSessionFactory;
use Framework\Router\Interface\RouterInterface;
use Framework\Renderer\Twig\TwigRendererFactory;
use Framework\Http\Interface\AppRequestInterface;
use Framework\Session\Interface\SessionInterface;
use Framework\Router\AltoRouter\AltoRouterFactory;
use Framework\Renderer\Interface\RendererInterface;
use Framework\Http\Interface\ResponseFactoryInterface;
use Framework\Http\Request\Guzzle\GuzzleRequestFactory;
use Framework\Http\Response\Guzzle\GuzzleResponseFactory;
use Framework\Log\Monolog\MonologFactory;

return [
    // Renderer
    RendererInterface::class => 
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
    AppRequestInterface::class => DI\factory(RequestFactory::class)
        ->parameter('requestFactory', DI\get(GuzzleRequestFactory::class)),
    GuzzleRequestFactory::class => DI\autowire(),

    ResponseFactoryInterface::class => DI\get(GuzzleResponseFactory::class),
    GuzzleResponseFactory::class => DI\autowire(),

    // Router
    RouterInterface::class =>
        DI\factory(RouterFactory::class)->parameter('routerFactory', DI\get(AltoRouterFactory::class)),
        
    AltoRouterFactory::class => DI\autowire(),
    "app.routes" => [],

    // Session
    SessionInterface::class => DI\factory(NativeSessionFactory::class),
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
    ],

    // Modules
    "Blog\\*Module\\Controller\\*Controller" => DI\create("Blog\\*Module\\Controller\\*Controller")
        ->property('containers', DI\get("app.container_controller"))
    ,

    // Controller
    "app.container_controller" => [
        "renderer" => DI\get(RendererInterface::class),
        "request" => DI\get(AppRequestInterface::class),
        "response" => DI\get(ResponseFactoryInterface::class),
    ],

    // Logger
    LoggerInterface::class => DI\factory(MonologFactory::class),
    "app.file_log" => dirname(__DIR__) . "/var/log/app.log"
];