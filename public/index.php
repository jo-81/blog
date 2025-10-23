<?php

use Framework\Application;
use Framework\Module\ModuleRegistry;

require_once \dirname(__DIR__) . "/vendor/autoload.php";

$moduleRegistry = new ModuleRegistry;

$app = new Application($moduleRegistry);
$app
    ->registerFile(dirname(__DIR__) . "/config/framework.php")
    ->init()
;