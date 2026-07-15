<?php

declare(strict_types=1);

namespace Framework\Form;

class FormBuilder implements FormBuilderInterface
{
    private array $fields = [];

    // 🚀 On injecte la donnée et les options dans le constructeur
    public function __construct(
        private mixed $data = null,
        private array $options = [],
    ) {}

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

    // 🚀 Permet d'accéder à l'entité (ex: le Tag) depuis le FormType
    public function getData(): mixed
    {
        return $this->data;
    }

    // 🚀 Permet d'accéder aux options globales depuis le FormType
    public function getOptions(): array
    {
        return $this->options;
    }
}
