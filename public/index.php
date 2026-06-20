<?php

use Dotenv\Dotenv;
use Framework\Http\Kernel;
use Framework\Debug\ErrorHandler;
use Framework\Http\ResponseEmitter;

require_once dirname(__DIR__) . "/vendor/autoload.php";

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

$isDebug = filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN);

ErrorHandler::register($isDebug);

$container = require_once dirname(__DIR__) . "/config/container.php";

/** @var Kernel $kernel */
$kernel = $container->get(Kernel::class);

/** @var ResponseEmitter $emitter */
$emitter = $container->get(ResponseEmitter::class);

// 2. Exécution du cycle de vie HTTP
$response = $kernel->handle();
$emitter->emit($response);
