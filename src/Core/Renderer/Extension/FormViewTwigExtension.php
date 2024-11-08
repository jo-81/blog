<?php

namespace Blog\Core\Renderer\Extension;

use Blog\Core\Form\AbstractFormType;
use Twig\Extension\AbstractExtension;

final class FormViewTwigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new \Twig\TwigFunction('form', [$this, 'doForm'], ['is_safe' => ['html']]),
            // new \Twig\TwigFunction('formRow', [$this, 'doFormRow'], ['is_safe' => ['html']]),
            // new \Twig\TwigFunction('formStart', [$this, 'doFormStart'], ['is_safe' => ['html']]),
            // new \Twig\TwigFunction('formEnd', [$this, 'doFormEnd'], ['is_safe' => ['html']]),
        ];
    }

    public function doForm(AbstractFormType $form): string
    {
        return $form->render();
    }

    // public function doFormRow(AbstractFormType $form, string $fieldName): string
    // {
    //     return $form->build($fieldName);
    // }

    // public function doFormStart(AbstractFormType $form): string
    // {
    //     return $form->buildStart();
    // }

    // public function doFormEnd(AbstractFormType $form): string
    // {
    //     return $form->buildEnd();
    // }
}
