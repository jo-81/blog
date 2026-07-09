<?php

declare(strict_types=1);

namespace Framework\Form\Field;

class PasswordType implements FieldTypeInterface
{
    public function getBlockName(): string
    {
        return 'password_widget';
    }
}
