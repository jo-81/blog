<?php

use Framework\Http\Router\Route;
use App\Controller\TestController;
use Framework\Http\Middlewares\WhoopsMiddleware;
use Framework\Http\Middlewares\RoutingMiddleware;
use Framework\Http\Middlewares\ErrorHandlingMiddleware;
use Framework\Http\Middlewares\RequestHandlerMiddleware;


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