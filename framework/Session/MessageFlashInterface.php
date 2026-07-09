<?php

declare(strict_types=1);

namespace Framework\Session;

interface MessageFlashInterface
{
    /**
     * Ajoute un ou plusieurs messages flash pour une catégorie donnée.
     *
     * @param string $type ex: 'success', 'error', 'info'
     * @param string|array $message Un message texte ou un tableau de messages
     */
    public function add(string $type, string|array $message): void;

    /**
     * Récupère tous les messages d'un type précis et les supprime de la session.
     */
    public function get(string $type): array;

    /**
     * Récupère TOUS les messages flash et vide le stockage de session.
     */
    public function all(): array;

    /**
     * Vérifie s'il existe des messages pour un type précis.
     */
    public function has(string $type): bool;
}
