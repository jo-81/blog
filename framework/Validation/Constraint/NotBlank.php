<?php

declare(strict_types=1);

namespace Framework\Validation\Constraint;

class NotBlank extends AbstractConstraint
{
    protected string $message = 'Ce champ ne peut pas être vide.';

    public function validate(mixed $value): bool|string
    {
        if (null === $value || '' === trim((string) $value)) {
            return $this->message;
        }

        return true;
    }
}
