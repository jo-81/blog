<?php

declare(strict_types=1);

namespace Framework\Form\Field;

class TexareaType implements FieldTypeInterface
{
    public function getBlockName(): string
    {
        return 'texarea_widget';
    }
}
