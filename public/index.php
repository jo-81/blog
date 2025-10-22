<?php

use Framework\Application;

require_once \dirname(__DIR__) . "/vendor/autoload.php";

$app = new Application();
$app
    ->registerFile(dirname(__DIR__) . "/config/framework.php")
    ->init()
;