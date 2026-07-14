<?php

declare(strict_types=1);

namespace Framework\Form\Field;

class ColorType implements FieldTypeInterface
{
    public function getBlockName(): string
    {
        return 'color_widget';
    }
}
