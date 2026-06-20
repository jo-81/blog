<?php

use Framework\Http\Kernel;
use Framework\Http\ResponseEmitter;

require_once dirname(__DIR__) . "/vendor/autoload.php";

$container = require_once dirname(__DIR__) . "/config/container.php";

/** @var Kernel $kernel */
$kernel = $container->get(Kernel::class);

/** @var ResponseEmitter $emitter */
$emitter = $container->get(ResponseEmitter::class);

// 2. Exécution du cycle de vie HTTP
$response = $kernel->handle();
$emitter->emit($response);
