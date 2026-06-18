<?php

use DI\ContainerBuilder;
use Framework\Support\ConfigGuard;
use Framework\Support\ConfigScanner;

$builder = new ContainerBuilder();

$systemConfigPath = __DIR__ . '/system.php';
$servicesConfigPath = __DIR__ . '/services.php';

// 1. Étape de sécurité centralisée pour vos briques indispensables
ConfigGuard::requireFiles([$systemConfigPath, $servicesConfigPath]);

// 2. Chargement ordonné de l'infrastructure de base (Priorité basse)
$builder->addDefinitions($systemConfigPath);

// 3. Détection et chargement automatique des AUTRES fichiers (Priorité haute)
// On exclut container.php, system.php et services.php pour éviter de les doubler
$excludes = ['container.php', 'system.php', 'services.php'];
$configFiles = ConfigScanner::scan(__DIR__, $excludes);

foreach ($configFiles as $absolutePath) {
    $builder->addDefinitions($absolutePath);
}

// 4. Chargement des services et factories (Priorité absolue)
$builder->addDefinitions($servicesConfigPath);

return $builder->build();