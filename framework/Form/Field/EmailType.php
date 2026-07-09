<?php

declare(strict_types=1);

namespace Framework\Form\Field;

class EmailType implements FieldTypeInterface
{
    public function getBlockName(): string
    {
        return 'email_widget';
    }
}
