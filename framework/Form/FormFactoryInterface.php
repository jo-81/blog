<?php

declare(strict_types=1);

namespace Framework\Form;

interface FormFactoryInterface
{
    public function create(string $formType, mixed $data = null, array $options = []): FormInterface;
}
