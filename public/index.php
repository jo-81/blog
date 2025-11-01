<?php

use Blog\HomeModule\HomeModule;
use Framework\Application;
use Framework\Middleware\MiddlewareHandler;
use Framework\Module\ModuleRegistry;
use Tests\Fixtures\Module\ExampleModule;
use Framework\Middleware\MiddlewareRegistry;
use Framework\Middleware\Middlewares\TrailingSlashMiddleware;

use function Http\Response\send;

require_once \dirname(__DIR__) . "/vendor/autoload.php";

$moduleRegistry = new ModuleRegistry;
$moduleRegistry->registerModule(HomeModule::class);

$app = new Application($moduleRegistry);
$app
    ->registerFile(dirname(__DIR__) . "/config/framework.php")
    ->registerFile(dirname(__DIR__) . "/config/middlewares.php")
    ->init()
;

$response = $app->start();

send($response);