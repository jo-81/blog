<?php

declare(strict_types=1);

namespace Framework;

use DI\ContainerBuilder;
use Framework\Module\ModuleRegistry;
use Psr\Container\ContainerInterface;
use Framework\Exception\FileConfigurationException;

/**
 * Application
 * Permet de récupérer les différents modules et fichiers de configuration pour démarrer l'application
 */
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
        $_ENV['APP_ENV'] = $this->environment;
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
     * Enregistre un fichier de configuration.
     *
     * @param string $file Chemin absolu vers le fichier de configuration
     *
     * @throws FileConfigurationException Si le fichier est invalide, inaccessible ou déjà enregistré
     */
    public function registerFile(string $file): static
    {
        if (\in_array($file, $this->files, true)) {
            throw new FileConfigurationException(\sprintf("Le fichier %s est déjà enregistré.", $file));
        }

        if (! \file_exists($file)) {
            throw new FileConfigurationException(\sprintf("Le fichier %s n'existe pas.", $file));
        }

        $fileInfo = new \SplFileInfo($file);
        if ($fileInfo->getExtension() !== 'php') {
            throw new FileConfigurationException(\sprintf("Le fichier %s ne possède pas l'extension .php.", $file));
        }

        if (!$fileInfo->isReadable()) {
            throw new FileConfigurationException(\sprintf("Le fichier %s n'est pas lisible.", $file));
        }

        $this->files[] = $file;

        return $this;
    }

    /**
     * Retourne les fichiers de configuration enregistrés.
     *
     * @return list<string> Chemins absolus
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * Initialise le conteneur d'injection de dépendances.
     *
     * Charge automatiquement les fichiers de configuration des modules
     * et applique les optimisations en environnement de production.
     *
     * @throws FileConfigurationException Si aucun fichier n'est enregistré
     * @throws \RuntimeException Si le conteneur est déjà initialisé
     */
    public function init(): static
    {
        // Ajout des fichiers de configuration des modules
        foreach ($this->moduleRegistry->getConfigFiles() as $moduleFile) {
            $this->registerFile($moduleFile);
        }

        if (empty($this->files)) {
            throw new FileConfigurationException("Aucun fichier de configuration n'est enregistré.");
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

    /**
     * Récupère le conteneur d'injection de dépendances.
     *
     * @throws \RuntimeException Si le conteneur n'est pas encore initialisé
     */
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
