<?php

declare(strict_types=1);

namespace Framework\Form;

class FormBuilder implements FormBuilderInterface
{
    private array $fields = [];

    public function add(string $name, string $fieldType, array $options = []): self
    {
        $this->fields[$name] = [
            'type'    => $fieldType,
            'options' => array_merge([
                'icon' => true,
                'label' => $name,
                'value' => null,
                'constraints' => [],
            ], $options),
        ];

        return $this;
    }

    public function getFields(): array
    {
        return $this->fields;
    }
}
