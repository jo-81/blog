<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CategoryRepository;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Table\Index;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Cycle\Annotated\Annotation\Relation\Inverse;

#[Entity(
    role: 'category',
    table: 'category',
    repository: CategoryRepository::class,
)]
#[Index(columns: ['name'], unique: true)]
#[Index(columns: ['slug'], unique: true)]
class Category extends Term
{
    #[Column(type: 'string(255)', nullable: true)]
    private ?string $description = null;

    #[HasMany(
        target: Post::class,
        inverse: new Inverse(as: 'category', type: 'belongsTo'),
    )]
    private array $posts = [];

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPosts(): array
    {
        return $this->posts;
    }
}
