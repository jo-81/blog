<?php

declare(strict_types=1);

namespace Framework\Form\Field;

class TextType implements FieldTypeInterface
{
    public function getBlockName(): string
    {
        return 'text_widget';
    }
}
