<?php

use DI\ContainerBuilder;
use Framework\Support\ConfigGuard;
use Framework\Support\ConfigScanner;

$builder = new ContainerBuilder();
$builder->useAttributes(true);

$servicesConfigPath = __DIR__ . '/services.php';

ConfigGuard::requireFiles([$servicesConfigPath]);

$excludes = ['container.php', 'services.php'];
$configFiles = ConfigScanner::scan(__DIR__, $excludes);

foreach ($configFiles as $absolutePath) {
    $builder->addDefinitions($absolutePath);
}

$builder->addDefinitions($servicesConfigPath);

return $builder->build();