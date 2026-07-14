<?php

declare(strict_types=1);

namespace App\Form\Tag;

use App\Repository\TagRepository;
use Framework\Form\Field\TextType;
use Framework\Form\Field\ColorType;
use Framework\Form\FormBuilderInterface;
use Framework\Validation\Constraint\Unique;
use Framework\Validation\Constraint\NotBlank;

class TagFormType
{
    public function __construct(private TagRepository $tagRepository) {}

    public function buildForm(FormBuilderInterface $builder): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nom',
                'placeholder' => 'ex : async',
                'constraints' => [
                    new NotBlank(),
                    new Unique($this->tagRepository, 'name'),
                ],
            ])
            ->add('color', ColorType::class, [
                'required' => true,
                'constraints' => [
                    new Unique($this->tagRepository, 'color'),
                ],
                'label' => 'Couleur',
            ])
        ;
    }
}
