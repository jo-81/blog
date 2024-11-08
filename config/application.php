<?php

use function DI\get;
use function DI\create;

use Blog\Controller\Admin\TagController;
use Blog\Controller\Admin\UserController;
use Blog\Controller\Auth\LoginController;
use Blog\Controller\Front\HomeController;
use Blog\Controller\Admin\CommentController;
use Blog\Controller\Auth\RegisterController;
use Blog\Controller\Front\ArticleController;
use Blog\Controller\Admin\CategoryController;
use Blog\Controller\Admin\DashboardController;
use Blog\Controller\Auth\ResetPasswordController;
use Blog\Controller\Admin\ArticleController as ArticleAdminController;

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

    DashboardController::class => create(DashboardController::class)
        ->property('renderer', get("app.renderer_interface")),

    ArticleAdminController::class => create(ArticleAdminController::class)
        ->property('renderer', get("app.renderer_interface")),

    TagController::class => create(TagController::class)
        ->property('renderer', get("app.renderer_interface")),

    CategoryController::class => create(CategoryController::class)
        ->property('renderer', get("app.renderer_interface")),

    CommentController::class => create(CommentController::class)
        ->property('renderer', get("app.renderer_interface")),

    UserController::class => create(UserController::class)
        ->property('renderer', get("app.renderer_interface")),
];