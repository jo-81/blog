<?php

use Framework\Http\Router\Route;
use App\Middlewares\RoutingMiddleware;
use App\Middlewares\MessageTestMiddleware;
use App\Middlewares\ErrorHandlingMiddleware;

// Configuration de l'infrastructure

return [
    /*
    |--------------------------------------------------------------------------
    | Pile de Middlewares Globaux
    |--------------------------------------------------------------------------
    | Ces middlewares sont exécutés dans l'ordre pour chaque requête HTTP.
    */
    'app.middlewares' => [
        ErrorHandlingMiddleware::class,
        RoutingMiddleware::class,
        MessageTestMiddleware::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    */
    'app.routes' => [
        new Route(['GET'], "/", fn() => 'Homepage'),
    ],
];