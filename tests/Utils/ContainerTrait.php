<?php

namespace Blog\Test\Utils;

use DI\ContainerBuilder;

trait ContainerTrait
{
    public function getContainer(): mixed
    {
        $configurationFolder = dirname(__DIR__, 2) . "/config";
        $fileConfigurations = array_diff(scandir($configurationFolder), ['..', '.']); /** @phpstan-ignore-line */
        $builder = new ContainerBuilder();

        foreach ($fileConfigurations as $file) {
            $builder->addDefinitions($configurationFolder . "/$file");
        }

        $container = $builder->build();

        return $container;
    }
}
