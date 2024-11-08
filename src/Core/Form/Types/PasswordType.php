<?php

namespace Blog\Core\Form\Types;

use Blog\Core\Form\AbstractType;

class PasswordType extends AbstractType
{
    public function getType(): string
    {
        return "password";
    }
}
