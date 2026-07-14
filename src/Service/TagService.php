<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Tag;
use Framework\Utils\Slugger;
use Framework\Database\EntityManagerInterface;

class TagService
{
    public function __construct(private EntityManagerInterface $em) {}

    public function create(Tag $tag): void
    {
        if (is_null($tag->getSlug())) {
            $tag->setSlug(Slugger::slugify($tag->getName()));
        }

        $this->em->persist($tag);
        $this->em->flush();
    }
}
