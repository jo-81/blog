<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TagRepository;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

#[Entity(
    role: 'tag',
    table: 'tag',
    repository: TagRepository::class,
)]
class Tag
{
    public const PAGINATION = 6;

    #[Column(type: 'primary')]
    private ?int $id = null; /** @phpstan-ignore-line */

    #[Column(type: 'string(100)', unique: true)]
    private ?string $name = null;

    #[Column(type: 'string(255)', unique: true)]
    private ?string $slug = null;

    #[Column(type: 'string(55)', unique: true)]
    private ?string $color = null;

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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color = null): self
    {
        $this->color = $color;

        return $this;
    }
}
