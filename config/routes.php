<?php

use Framework\Http\Router\Route;
use App\Controller\HomeController;

return [
    'app.routes' => [
        new Route(['GET'], "/", [HomeController::class, 'index'], 'homepage'),
    ],
];