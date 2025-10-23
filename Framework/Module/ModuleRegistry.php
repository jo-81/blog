<?php

declare(strict_types=1);

namespace Framework\Module;

use Framework\Exception\ModuleException;

final class ModuleRegistry
{
    /** @var array<string, string> */
    private array $modules = [];

    public function registerModule(string $moduleName): static
    {
        if ($this->isModuleExist($moduleName::getName())) {
            throw new ModuleException(\sprintf("Le module %s est déjà présent.", $moduleName::getName()));
        }

        $this->modules[$moduleName::getName()] = $moduleName;

        return $this;
    }

    /**
     * getModules
     *
     * @return array<string, string>
     */
    public function getModules(): array
    {
        return $this->modules;
    }

    public function isModuleExist(string $moduleName): bool
    {
        return \array_key_exists($moduleName, $this->modules);
    }

    /**
     * Retourne la liste des fichiers de configuration des modules enregistrés.
     *
     * @return string[]
     */
    public function getConfigFiles(): array
    {
        $files = [];
        foreach ($this->modules as $module) {
            $file = $module::DEFINITION;
            if (empty($file)) {
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
