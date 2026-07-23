<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TagRepository;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Table\Index;
use Cycle\Annotated\Annotation\Relation\ManyToMany;

#[Entity(
    role: 'tag',
    table: 'tag',
    repository: TagRepository::class,
)]
#[Index(columns: ['name'], unique: true)]
#[Index(columns: ['slug'], unique: true)]
class Tag extends Term
{
    #[Column(type: 'string(55)', unique: true)]
    protected ?string $color = null;

    #[ManyToMany(target: Post::class, through: PostTag::class)]
    private array $posts = [];

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color = null): self
    {
        $this->color = $color;

        return $this;
    }

    public function getPosts(): array
    {
        return $this->posts;
    }
}
