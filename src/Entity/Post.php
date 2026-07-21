<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enums\PostStatus;
use App\Repository\PostRepository;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Table\Index;
use Cycle\Annotated\Annotation\GeneratedValue;
use Cycle\Annotated\Annotation\Relation\Inverse;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Relation\ManyToMany;

#[Entity(
    role: 'post',
    table: 'post',
    repository: PostRepository::class,
)]
#[Index(columns: ['title'], unique: true)]
#[Index(columns: ['slug'], unique: true)]
class Post
{
    #[Column(type: 'primary')]
    #[GeneratedValue(onInsert: true)]
    private ?int $id = null;

    #[Column(type: 'string(255)')]
    private ?string $title = null;

    #[Column(type: 'string(255)')]
    private ?string $slug = null;

    #[Column(type: 'longText')]
    private ?string $content = null;

    #[Column(type: 'tinyText')]
    private ?string $excerpt = null;

    #[Column(type: 'datetime', default: 'CURRENT_TIMESTAMP', name: 'created_at')]
    #[GeneratedValue(onInsert: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[Column(type: 'datetime', default: 'CURRENT_TIMESTAMP', name: 'edited_at')]
    #[GeneratedValue(beforeUpdate: true)]
    private ?\DateTimeInterface $editedAt = null;

    #[Column(type: 'string(32)', default: 'draft', typecast: PostStatus::class)]
    private PostStatus $status = PostStatus::DRAFT;

    #[BelongsTo(target: Image::class, nullable: true)]
    private ?Image $image = null;

    #[BelongsTo(target: PostMetaSeo::class, nullable: true)]
    private ?PostMetaSeo $meta = null;

    #[BelongsTo(target: Category::class, innerKey: 'category_id', nullable: true)]
    private ?Category $category = null;

    #[ManyToMany(
        target: Tag::class,
        through: PostTag::class,
        inverse: new Inverse(as: 'posts', type: 'manyToMany'),
    )]
    private array $tags = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title = null): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug = null): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content = null): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt = null): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    public function setExcerpt(?string $excerpt): self
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    public function getEditedAt(): ?\DateTimeInterface
    {
        return $this->editedAt;
    }

    public function setEditedAt(?\DateTimeInterface $editedAt): self
    {
        $this->editedAt = $editedAt;

        return $this;
    }

    public function getStatus(): PostStatus
    {
        return $this->status;
    }

    public function setStatus(PostStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        foreach ($this->tags as $existingTag) {
            if ($existingTag->getId() === $tag->getId()) {
                return $this;
            }
        }

        $this->tags[] = $tag;

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags = array_filter(
            $this->tags,
            static fn(Tag $t) => $t !== $tag,
        );

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getMeta(): ?PostMetaSeo
    {
        return $this->meta;
    }

    public function setMeta(?PostMetaSeo $meta): self
    {
        $this->meta = $meta;

        return $this;
    }
}
