<?php

use App\Controller\Admin\DashboardController;
use App\Controller\Admin\TermController;
use App\Controller\Auth\LoginController;
use App\Controller\HomeController;
use Framework\Http\Router\Route;

return [
    'app.routes' => [
        new Route(['GET'], "/", [HomeController::class, 'index'], 'homepage'),
        new Route(['GET', 'POST'], "/connexion", [LoginController::class, 'login'], 'app.login'),
        new Route(['GET'], "/logout", [LoginController::class, 'logout'], 'app.logout'),

        new Route(['GET'], '/dashboard', [DashboardController::class, 'dashboard'], 'dashboard'),

        new Route(['GET'], '/admin/{termName}', [TermController::class, 'index'], 'admin.term.list'),
        new Route(['POST'], '/admin/{termName}/create', [TermController::class, 'create'], 'admin.term.create'),
    ],
];