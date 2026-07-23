<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PostTagRepository;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\GeneratedValue;
use Cycle\Annotated\Annotation\Relation\BelongsTo;

#[Entity(
    role: 'post_tag',
    table: 'post_tag',
    repository: PostTagRepository::class,
)]
class PostTag
{
    #[Column(type: 'primary')]
    #[GeneratedValue(onInsert: true)]
    private int $id;

    #[BelongsTo(target: Post::class, innerKey: 'post_id', fkCreate: true)]
    private Post $post;

    #[BelongsTo(target: Tag::class, innerKey: 'tag_id', fkCreate: true)]
    private Tag $tag;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function setPost(Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getTag(): Tag
    {
        return $this->tag;
    }

    public function setTag(Tag $tag): self
    {
        $this->tag = $tag;

        return $this;
    }
}
