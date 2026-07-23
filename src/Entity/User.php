<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enums\UserRole;
use App\Repository\UserRepository;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Table\Index;
use Cycle\Annotated\Annotation\GeneratedValue;

#[Entity(
    role: 'user',
    table: 'user',
    repository: UserRepository::class,
)]
#[Index(columns: ['username'], unique: true)]
#[Index(columns: ['email'], unique: true)]
class User
{
    #[Column(type: 'primary')]
    #[GeneratedValue(onInsert: true)]
    private int $id;

    #[Column(type: 'string(100)', unique: true)]
    private ?string $username = null;

    #[Column(type: 'string(255)', unique: true)]
    private ?string $email = null;

    #[Column(type: 'string(255)')]
    private ?string $password = null;

    #[Column(type: 'string', default: 'user', typecast: UserRole::class)]
    private UserRole $role = UserRole::USER;

    #[Column(type: 'datetime', name: 'created_at')]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username = null): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password = null): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email = null): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function setRole(UserRole $role): self
    {
        $this->role = $role;
        return $this;
    }
}
