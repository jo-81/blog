<?php

declare(strict_types=1);

namespace Framework\Validation\Constraint;

abstract class AbstractConstraint implements ConstraintInterface
{
    protected string $message = "Cette valeur n'est pas valide.";

    public function __construct(array $options = [])
    {
        if (isset($options['message'])) {
            $this->message = $options['message'];
        }
    }
}
