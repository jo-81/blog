<?php

declare(strict_types=1);

namespace Framework\Support;

/**
 * Gardien de configuration.
 * Centralise les validations strictes sur les fichiers et paramètres d'infrastructure.
 */
class ConfigGuard
{
    /**
     * Vérifie l'existence de plusieurs fichiers de configuration critiques.
     * S'adapte selon l'environnement (DEV/PROD).
     *
     * @param array<string> $filePaths Tableau contenant les chemins absolus des fichiers.
     * @throws \RuntimeException Si l'un des fichiers est manquant.
     */
    public static function requireFiles(array $filePaths): void
    {
        // On récupère l'environnement (par défaut 'DEV' si non défini)
        $env = $_ENV['APP_ENV'] ?? getenv('APP_ENV') ?: 'DEV';

        foreach ($filePaths as $path) {
            if (!file_exists($path)) {
                $fileName = basename($path);

                // Ajustement du comportement selon l'environnement
                if ($env === 'PROD') {
                    // En production, on reste discret sur l'arborescence du serveur
                    // On log l'erreur complète en tâche de fond si nécessaire, et on lance un message générique
                    throw new \RuntimeException(
                        "Une erreur interne critique est survenue lors de l'initialisation du système.",
                    );
                }

                // En développement (DEV), on donne un maximum de détails pour déboguer
                throw new \RuntimeException(
                    "Erreur d'infrastructure [Mode: {$env}] : Le fichier de configuration critique 'config/{$fileName}' est introuvable au chemin : {$path}.",
                );
            }
        }
    }
}
