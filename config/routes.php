<?php

use App\Controller\Auth\LoginController;
use Framework\Http\Router\Route;
use App\Controller\HomeController;

return [
    'app.routes' => [
        new Route(['GET'], "/", [HomeController::class, 'index'], 'homepage'),
        new Route(['GET', 'POST'], "/connexion", [LoginController::class, 'login'], 'app.login'),
        new Route(['GET'], "/logout", [LoginController::class, 'logout'], 'app.logout'),
    ],
];