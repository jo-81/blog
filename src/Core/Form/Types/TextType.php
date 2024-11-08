<?php

namespace Blog\Core\Form\Types;

use Blog\Core\Form\AbstractType;

class TextType extends AbstractType
{
    public function getType(): string
    {
        return "text";
    }
}
