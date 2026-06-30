<?php

declare(strict_types=1);

namespace Framework\Database;

use App\Entity\User;

interface UserRepositoryInterface
{
    /**
     * Trouve UN SEUL utilisateur selon plusieurs critères.
     * Exemple : $repository->findOneBy(['email' => 'admin@blog.com', 'role' => UserRole::ADMIN]);
     *
     * @param array<string, mixed> $criteria
     */
    public function findOneBy(array $criteria): ?User;

    /**
     * Trouve TOUS les utilisateurs correspondant à plusieurs critères.
     * Exemple : $repository->findBy(['role' => UserRole::PHOTOGRAPHER]);
     *
     * @param array<string, mixed> $criteria
     * @return array<User>
     */
    public function findBy(array $criteria): array;
}
