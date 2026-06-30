<?php

namespace Framework\Factories;

use App\Entity\User;
use Framework\Database\EntityManagerInterface;
use Framework\Database\UserRepositoryInterface;
use Psr\Container\ContainerInterface;

class CycleUserFactory
{
    public function __invoke(ContainerInterface $container): UserRepositoryInterface
    {
        $entityManager = $container->get(EntityManagerInterface::class);

        return $entityManager->getRepository(User::class);
    }
}