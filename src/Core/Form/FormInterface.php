<?php

namespace Blog\Core\Form;

interface FormInterface
{
    /**
     * getfields
     *
     * @return array<string, array<string, mixed>>
     */
    public function getfields(): array;

    /**
     * addField
     *
     * @param  string $name
     * @param  string $type
     * @param  string $label
     * @param  array<string, mixed> $options
     * @return void
     */
    public function addField(string $name, string $type, string $label, array $options = []);

    public function render(): string;

    public function setAction(string $action): self;

    public function setMethod(string $method): self;
}
