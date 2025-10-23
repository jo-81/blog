<?php

declare(strict_types=1);

namespace Framework\Module;

use Framework\Exception\ModuleException;

/**
 * Registre centralisé pour la gestion des modules de l'application.
 */
final class ModuleRegistry
{
    /**
     * Modules enregistrés [nom normalisé => FQCN].
     *
     * @var array<string, string>
     */
    private array $modules = [];

    /**
     * Enregistre un module dans le système.
     *
     * @param string $moduleName FQCN du module
     *
     * @throws ModuleException Si la classe n'existe pas, n'implémente pas ModuleInterface, ou est déjà enregistrée
     */
    public function registerModule(string $moduleName): static
    {
        if (!class_exists($moduleName)) {
            throw new ModuleException(\sprintf("La classe %s n'existe pas.", $moduleName));
        }

        if (!is_subclass_of($moduleName, ModuleInterface::class)) {
            throw new ModuleException(\sprintf("La classe %s doit implémenter ModuleInterface.", $moduleName));
        }

        $name = $moduleName::getName();
        if ($this->isModuleExist($name)) {
            throw new ModuleException(\sprintf("Le module %s est déjà présent.", $name));
        }

        $this->modules[$name] = $moduleName;

        return $this;
    }

    /**
     * Retourne la liste des modules enregistrés.
     *
     * @return array<string, string> Tableau associatif [nom du module => classe FQCN]
     */
    public function getModules(): array
    {
        return $this->modules;
    }

    /**
     * Vérifie si un module existe dans le registre.
     */
    public function isModuleExist(string $moduleName): bool
    {
        return \array_key_exists($moduleName, $this->modules);
    }

    /**
     * Retourne la liste des fichiers de configuration des modules enregistrés.
     *
     * @return list<string> Chemins absolus vers les fichiers de configuration
     *
     * @throws ModuleException Si un fichier de configuration déclaré n'existe pas
     */
    public function getConfigFiles(): array
    {
        $files = [];
        foreach ($this->modules as $module) {
            $file = $module::DEFINITION;
            if ($file === null || $file === '') {
                continue;
            }

            if (! \file_exists($file)) {
                throw new ModuleException(\sprintf("Le fichier de configuration pour le module %s n'existe pas", $module::getName()));
            }
            $files[] = $file;
        }

        return $files;
    }
}
