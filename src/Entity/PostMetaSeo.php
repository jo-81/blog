<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use App\Repository\PostMetaSeoRepository;
use Cycle\Annotated\Annotation\Table\Index;
use Cycle\Annotated\Annotation\GeneratedValue;

#[Entity(
    role: 'post_meta_seo',
    table: 'post_meta_seo',
    repository: PostMetaSeoRepository::class,
)]
#[Index(columns: ['title'], unique: true)]
class PostMetaSeo
{
    #[Column(type: 'primary')]
    #[GeneratedValue(onInsert: true)]
    private ?int $id = null;

    #[Column(type: 'string(255)')]
    private ?string $title = null;

    #[Column(type: 'string(255)')]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

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
