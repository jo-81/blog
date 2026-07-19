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
class Tag extends Term
{
    #[Column(type: 'string(55)', unique: true)]
    protected ?string $color = null;

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
