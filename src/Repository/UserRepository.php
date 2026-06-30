<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Cycle\ORM\Select\Repository;
use Framework\Database\UserRepositoryInterface;

class UserRepository extends Repository implements UserRepositoryInterface
{
    public function findOneBy(array $criteria): ?User
    {
        return $this->select()->where($criteria)->fetchOne();
    }

    public function findBy(array $criteria): array
    {
        return $this->select()->where($criteria)->fetchAll();
    }
}
