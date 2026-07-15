<?php

declare(strict_types=1);

namespace Framework\Form;

interface FormBuilderInterface
{
    public function add(string $name, string $type, array $options = []): self;

    public function getFields(): array;

    public function getData(): mixed;

    public function getOptions(): array;
}
