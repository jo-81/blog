<?php

declare(strict_types=1);

namespace Framework\Support;

use RegexIterator;
use RuntimeException;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class ConfigScanner
{
    /**
     * Récupère de manière récursive tous les fichiers de configuration PHP d'un dossier.
     *
     * @param string $directory Chemin absolu du dossier à scanner.
     * @param array<string> $excludes Noms de fichiers spécifiques à ignorer (ex: ['container.php']).
     * @return array<string> Liste des chemins absolus des fichiers PHP trouvés.
     * @throws RuntimeException Si le dossier n'existe pas.
     */
    public static function scan(string $directory, array $excludes = []): array
    {
        $directory = rtrim($directory, '/\\');

        if (!file_exists($directory) || !is_dir($directory)) {
            throw new RuntimeException(sprintf("Le dossier de configuration '%s' n'existe pas ou est invalide.", $directory));
        }

        // 1. On crée l'itérateur pour parcourir le dossier récursivement
        // SKIP_DOTS permet d'ignorer automatiquement '.' et '..'
        $directoryIterator = new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directoryIterator);

        // 2. On filtre pour ne garder QUE les fichiers se terminant par .php
        $phpFiles = new RegexIterator($iterator, '/\.php$/i');

        $validFiles = [];

        /** @var \SplFileInfo $file */
        foreach ($phpFiles as $file) {
            // $file est une instance de SplFileInfo, ce qui est très puissant
            $fileName = $file->getFilename();

            // On vérifie les exclusions
            if (in_array($fileName, $excludes, true)) {
                continue;
            }

            // On récupère le chemin absolu propre
            $validFiles[] = $file->getRealPath();
        }

        // Optionnel : Trier les fichiers par ordre alphabétique pour garantir un ordre de chargement stable
        sort($validFiles);

        return $validFiles;
    }
}
