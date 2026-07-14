<?php

use App\Controller\Admin\DashboardController;
use App\Controller\Admin\TagController;
use App\Controller\Auth\LoginController;
use Framework\Http\Router\Route;
use App\Controller\HomeController;

return [
    'app.routes' => [
        new Route(['GET'], "/", [HomeController::class, 'index'], 'homepage'),
        new Route(['GET', 'POST'], "/connexion", [LoginController::class, 'login'], 'app.login'),
        new Route(['GET'], "/logout", [LoginController::class, 'logout'], 'app.logout'),

        new Route(['GET'], '/dashboard', [DashboardController::class, 'dashboard'], 'dashboard'),
        new Route(['GET'], '/admin/tags', [TagController::class, 'index'], 'admin.tag.list'),
        new Route(['POST'], '/admin/tags/create', [TagController::class, 'create'], 'admin.tag.create'),
    ],
];