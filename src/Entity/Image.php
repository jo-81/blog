<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ImageRepository;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Table\Index;
use Cycle\Annotated\Annotation\GeneratedValue;

#[Entity(
    role: 'image',
    table: 'image',
    repository: ImageRepository::class,
)]
#[Index(columns: ['filename'], unique: true)]
class Image
{
    #[Column(type: 'primary')]
    #[GeneratedValue(onInsert: true)]
    private ?int $id = null;

    #[Column(type: 'string(255)', unique: true)]
    private ?string $filename = null;

    #[Column(type: 'string(255)', default: '')]
    private string $alt = '';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }
}
