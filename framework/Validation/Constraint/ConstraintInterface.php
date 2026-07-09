<?php

declare(strict_types=1);

namespace Framework\Validation\Constraint;

interface ConstraintInterface
{
    /**
     * Valide une valeur. Renvoie true si valide, ou une chaîne (le message d'erreur) si invalide.
     */
    public function validate(mixed $value): bool|string;
}
