<?php

namespace App\Tests\Traits;

use DI\ContainerBuilder;
use Framework\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

trait ContainerTrait
{
    public function getContainer(string $folder): ContainerInterface
    {
        if (!file_exists($folder)) {
            throw new NotFoundException("Le dossier de configuration n'existe pas");
        }

        $directory = new \RecursiveDirectoryIterator($folder, \FilesystemIterator::SKIP_DOTS);
        /** @var array<int, \SplFileInfo> */
        $iterator = new \RecursiveIteratorIterator($directory);

        $containerBuilder = new ContainerBuilder();
        foreach ($iterator as $file) {
            if (!$file->isFile()) {
                continue;
            }
            $containerBuilder->addDefinitions($file->getPathname());
        }

        return $containerBuilder->build();
    }
}
