<?php

declare(strict_types=1);

namespace Framework\Database;

interface EntityManagerInterface
{
    /**
     * Prépare une entité pour la création ou la mise à jour.
     */
    public function persist(object $entity): void;

    /**
     * Prépare une entité pour la suppression.
     */
    public function remove(object $entity): void;

    /**
     * Exécute toutes les opérations (INSERT, UPDATE, DELETE)
     * en base de données dans une seule transaction.
     */
    public function flush(): void;

    /**
     * Raccourci pour récupérer le repository d'une entité.
     *
     * @template T of object
     * @param class-string<T> $entityClass
     * @return mixed
     */
    public function getRepository(string $entityClass): mixed;
}
