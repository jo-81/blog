<?php

namespace App\Service;

use App\Entity\Category;
use Framework\Database\EntityManagerInterface;
use Framework\Utils\Slugger;

class CategoryService
{
    public function __construct(private EntityManagerInterface $em)
    {}

    public function create(Category $category): void
    {
        $category->setSlug(Slugger::slugify($category->getName()));

        $this->em->persist($category);
        $this->em->flush();
    }
}