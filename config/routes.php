<?php

use App\Controller\Admin\CategoryController;
use App\Controller\Admin\DashboardController;
use App\Controller\Admin\TagController;
use App\Controller\Auth\LoginController;
use App\Controller\HomeController;
use Framework\Http\Router\Route;

return [
    'app.routes' => [
        new Route(['GET'], "/", [HomeController::class, 'index'], 'homepage'),
        new Route(['GET', 'POST'], "/connexion", [LoginController::class, 'login'], 'app.login'),
        new Route(['GET'], "/logout", [LoginController::class, 'logout'], 'app.logout'),

        new Route(['GET'], '/dashboard', [DashboardController::class, 'dashboard'], 'dashboard'),
        new Route(['GET'], '/admin/tags', [TagController::class, 'index'], 'admin.tag.list'),
        new Route(['POST'], '/admin/tags/create[/{id}]', [TagController::class, 'persist'], 'admin.tag.create'),
        new Route(['GET'], '/admin/tags/edit/{id:\d+}', [TagController::class, 'edit'], 'admin.tag.edit'),
        new Route(['DELETE'], '/admin/tags/remove/{id:\d+}', [TagController::class, 'remove'], 'admin.tag.remove'),

        new Route(['GET'], '/admin/categories', [CategoryController::class, 'index'], 'admin.category.list'),
        new Route(['POST'], '/admin/categories/create[/{id}]', [CategoryController::class, 'persist'], 'admin.category.create'),
    ],
];