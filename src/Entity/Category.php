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
class Category
{
    public const PAGINATION = 6;

    #[Column(type: 'primary')]
    private ?int $id = null; /** @phpstan-ignore-line */

    #[Column(type: 'string(255)', unique: true)]
    private ?string $name = null;

    #[Column(type: 'string(255)', unique: true)]
    private ?string $slug = null;

    #[Column(type: 'string(255)', nullable: true)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name = null): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug = null)
    {
        $this->slug = $slug;

        return $this;
    }

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
