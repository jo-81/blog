<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\GeneratedValue;

abstract class Term
{
    public const PAGINATION = 6;

    #[Column(type: 'primary')]
    #[GeneratedValue(onInsert: true)]
    protected ?int $id = null;

    #[Column(type: 'string(255)', unique: true)]
    protected ?string $name = null;

    #[Column(type: 'string(255)', unique: true)]
    protected ?string $slug = null;

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

    public function setSlug(?string $slug = null): self
    {
        $this->slug = $slug;

        return $this;
    }
}
