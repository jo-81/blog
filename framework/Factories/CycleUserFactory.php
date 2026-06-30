<?php

declare(strict_types=1);

namespace Framework\Factories;

use App\Entity\User;
use Psr\Container\ContainerInterface;
use Framework\Database\EntityManagerInterface;
use Framework\Database\UserRepositoryInterface;

class CycleUserFactory
{
    public function __invoke(ContainerInterface $container): UserRepositoryInterface
    {
        $entityManager = $container->get(EntityManagerInterface::class);

        return $entityManager->getRepository(User::class);
    }
}
