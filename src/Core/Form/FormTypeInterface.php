<?php

namespace Blog\Core\Form;

interface FormTypeInterface
{
    public function getType(): string;

    /**
     * render
     *
     * @param  string $name
     * @param  string $label
     * @param  array<string, mixed> $fields
     * @return string
     */
    public function render(string $name, string $label, array $fields = []): string;
}
