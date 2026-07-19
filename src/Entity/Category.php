<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CategoryRepository;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

#[Entity(
    role: 'category',
    table: 'category',
    repository: CategoryRepository::class,
)]
class Category extends Term
{
    #[Column(type: 'string(255)', nullable: true)]
    private ?string $description = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
