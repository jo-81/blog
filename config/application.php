<?php

use function DI\get;
use function DI\create;

use Blog\Controller\Auth\LoginController;
use Blog\Controller\Front\HomeController;
use Blog\Controller\Auth\RegisterController;
use Blog\Controller\Front\ArticleController;
use Blog\Controller\Auth\ResetPasswordController;

return [
    HomeController::class => create(HomeController::class)
        ->property('renderer', get("app.renderer_interface")),

    ArticleController::class => create(ArticleController::class)
        ->property('renderer', get("app.renderer_interface")),

    LoginController::class => create(LoginController::class)
        ->property('renderer', get("app.renderer_interface")),

    RegisterController::class => create(RegisterController::class)
        ->property('renderer', get("app.renderer_interface")),

    ResetPasswordController::class => create(ResetPasswordController::class)
        ->property('renderer', get("app.renderer_interface")),
];