<?php

declare(strict_types=1);

namespace Framework\Session\Interface;

interface SessionInterface
{
    /**
     * Démarrer la session
     */
    public function start(): void;

    /**
     * Vérifier si la session est active
     */
    public function isStarted(): bool;

    /**
     * Récupérer une valeur
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Définir une valeur
     */
    public function set(string $key, mixed $value): void;

    /**
     * Vérifier si une clé existe
     */
    public function has(string $key): bool;

    /**
     * Supprimer une valeur
     */
    public function remove(string $key): void;

    /**
     * Récupérer et supprimer (flash message)
     */
    public function flash(string $key, mixed $default = null): mixed;

    /**
     * Récupérer toutes les données
     *
     * @return mixed[]
     */
    public function all(): array;

    /**
     * Nettoyer toutes les données
     */
    public function clear(): void;

    /**
     * Régénérer l'ID de session
     */
    public function regenerate(bool $deleteOldSession = true): void;

    /**
     * Détruire la session
     */
    public function destroy(): void;

    /**
     * Obtenir l'ID de session
     */
    public function getId(): string;

    /**
     * Sauvegarder les données de session
     */
    public function save(): void;
}
