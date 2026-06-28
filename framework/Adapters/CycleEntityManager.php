<?php

declare(strict_types=1);

namespace Framework\Adapters;

use Cycle\ORM\ORMInterface;
use Framework\Database\EntityManagerInterface;
use Cycle\ORM\EntityManager as CycleInternalManager;
use Cycle\ORM\RepositoryInterface as CycleRepositoryInterface;

/**
 * Implémentation concrète de l'EntityManager du Framework encapsulant Cycle ORM.
 *
 */
final class CycleEntityManager implements EntityManagerInterface
{
    private CycleInternalManager $transaction;

    public function __construct(private ORMInterface $orm)
    {
        $this->transaction = new CycleInternalManager($this->orm);
    }

    public function persist(object $entity): void
    {
        $this->transaction->persist($entity);
    }

    public function remove(object $entity): void
    {
        $this->transaction->delete($entity);
    }

    public function flush(): void
    {
        $this->transaction->run();
    }

    /**
     * @template T of object
     * @param class-string<T> $entityClass Le nom pleinement qualifié (FQCN) de l'entité (ex: User::class).
     * @return CycleRepositoryInterface<T> Le repository de Cycle ORM typé pour l'entité demandée.
     */
    public function getRepository(string $entityClass): mixed
    {
        /** @var CycleRepositoryInterface<T> */
        return $this->orm->getRepository($entityClass);
    }
}
