<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Category;
use Framework\Utils\Slugger;
use Framework\Database\EntityManagerInterface;

class CategoryService
{
    public function __construct(private EntityManagerInterface $em) {}

    public function create(Category $category): void
    {
        $category->setSlug(Slugger::slugify($category->getName()));

        $this->em->persist($category);
        $this->em->flush();
    }
}
