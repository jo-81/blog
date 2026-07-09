<?php

declare(strict_types=1);

namespace Framework\Validation\Constraint;

class Email extends AbstractConstraint
{
    protected string $message = "L'adresse email n'est pas valide.";

    public function validate(mixed $value): bool|string
    {
        // Si le champ est vide, on laisse NotBlank gérer. S'il y a une valeur, on la valide.
        if (null !== $value && '' !== trim((string) $value)) {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                return $this->message;
            }
        }

        return true;
    }
}
