<?php

declare(strict_types=1);

namespace Framework;

use DI\ContainerBuilder;
use Framework\Module\ModuleRegistry;
use Psr\Container\ContainerInterface;
use Framework\Exception\FileNotFoundException;

final class Application
{
    public const ENV_DEVELOPMENT = 'dev';

    public const ENV_PRODUCTION = 'prod';

    /** @var string[] */
    private array $files = [];

    private ?ContainerInterface $container = null;

    public function __construct(
        private ModuleRegistry $moduleRegistry,
        private string $environment = self::ENV_DEVELOPMENT
    ) {
    }

    public function isDevelopment(): bool
    {
        return $this->environment === self::ENV_DEVELOPMENT;
    }

    public function isProduction(): bool
    {
        return $this->environment === self::ENV_PRODUCTION;
    }

    /**
     * Enregistre un fichier de configuration
     */
    public function registerFile(string $file): static
    {
        if (\in_array($file, $this->files, true)) {
            throw new \RuntimeException(\sprintf("Le fichier %s est déjà enregistré.", $file));
        }

        if (! \file_exists($file)) {
            throw new FileNotFoundException($file);
        }

        $fileInfo = new \SplFileInfo($file);
        if ($fileInfo->getExtension() !== 'php') {
            throw new \RuntimeException(\sprintf("Le fichier %s ne possède pas l'extension .php.", $file));
        }

        if (!$fileInfo->isReadable()) {
            throw new \RuntimeException(\sprintf("Le fichier %s n'est pas lisible.", $file));
        }

        $this->files[] = $file;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * Initialise le container avec les différents fichiers de configuration
     */
    public function init(): static
    {
        // Ajout des fichiers de configuration des modules
        foreach ($this->moduleRegistry->getConfigFiles() as $moduleFile) {
            $this->registerFile($moduleFile);
        }

        if (empty($this->files)) {
            throw new \RuntimeException("Aucun fichier de configuration n'est enregistré.");
        }

        if ($this->container instanceof ContainerInterface) {
            throw new \RuntimeException("Le container est déjà initialisé.");
        }

        $containerBuilder = new ContainerBuilder();
        foreach ($this->files as $file) {
            $containerBuilder->addDefinitions($file);
        }

        $containerBuilder->addDefinitions([
            'app.environment' => $this->environment,
            'app' => $this,
        ]);

        if ($this->isProduction()) {
            $containerBuilder->enableCompilation($this->getDirectoryCache());
            $containerBuilder->writeProxiesToFile(true, $this->getDirectoryCache() . '/proxies');
        }

        $this->container = $containerBuilder->build();

        return $this;
    }

    public function getContainer(): ContainerInterface
    {
        if ($this->container === null) {
            throw new \RuntimeException("Le container n'est pas encore initialisé. Appelez init() d'abord.");
        }

        return $this->container;
    }

    public function reset(): static
    {
        $this->container = null;
        $this->files = [];

        return $this;
    }

    private function getDirectoryCache(): string
    {
        $cacheDir = dirname(__DIR__) . '/var/cache/container';

        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        return $cacheDir;
    }
}
