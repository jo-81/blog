<?php

declare(strict_types=1);

namespace App\Form\Tag;

use Framework\Form\Field\TextType;
use Framework\Form\Field\ColorType;
use Framework\Form\FormBuilderInterface;

class TagFormType
{
    public function buildForm(FormBuilderInterface $builder): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nom',
                'placeholder' => 'ex : async',
                'constraints' => [],
            ])
            ->add('slug', TextType::class, [
                'required' => false,
                'constraints' => [],
                'help' => 'Généré automatiquement depuis le nom.',
            ])
            ->add('color', ColorType::class, [
                'required' => true,
                'constraints' => [],
                'label' => 'Couleur',
            ])
        ;
    }
}
