<?php

use Framework\Http\Router\Route;
use App\Controller\TestController;
use App\Middlewares\WhoopsMiddleware;
use App\Middlewares\RoutingMiddleware;
use App\Middlewares\ErrorHandlingMiddleware;
use App\Middlewares\RequestHandlerMiddleware;

// Configuration de l'infrastructure

return [
    /*
    |--------------------------------------------------------------------------
    | Pile de Middlewares Globaux
    |--------------------------------------------------------------------------
    | Ces middlewares sont exécutés dans l'ordre pour chaque requête HTTP.
    */
    'app.middlewares' => [
        WhoopsMiddleware::class,
        ErrorHandlingMiddleware::class,
        RoutingMiddleware::class,
        RequestHandlerMiddleware::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    */
    'app.routes' => [
        new Route(['GET'], "/", [TestController::class, 'index'], 'homepage'),
    ],
];