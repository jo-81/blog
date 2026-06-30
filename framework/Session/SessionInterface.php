<?php

namespace Framework\Session;

interface SessionInterface
{
    /**
     * Démarre la session de manière sécurisée.
     */
    public function start(): void;

    /**
     * Récupère une valeur en session ou retourne la valeur par défaut.
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Définit une valeur en session.
     */
    public function set(string $key, mixed $value): void;

    /**
     * Vérifie si une clé existe en session.
     */
    public function has(string $key): bool;

    /**
     * Supprime une clé spécifique de la session.
     */
    public function remove(string $key): void;

    /**
     * Détruit complètement la session actuelle.
     */
    public function destroy(): void;
}