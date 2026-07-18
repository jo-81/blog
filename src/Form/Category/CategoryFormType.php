<?php

declare(strict_types=1);

namespace App\Form\Category;

use App\Repository\CategoryRepository;
use Framework\Form\Field\TexareaType;
use Framework\Form\Field\TextType;
use Framework\Form\FormBuilderInterface;
use Framework\Validation\Constraint\NotBlank;
use Framework\Validation\Constraint\Unique;

class CategoryFormType
{
    public function __construct(private CategoryRepository $categoryRepository) {}

    public function buildForm(FormBuilderInterface $builder): void
    {
        $category = $builder->getData();

        $categoryId = (is_object($category) && method_exists($category, 'getId')) ? $category->getId() : null;

        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nom',
                'placeholder' => 'ex : JavaScript',
                'constraints' => [
                    new NotBlank(),
                    new Unique($this->categoryRepository, 'name', $categoryId),
                ],
            ])
            ->add('description', TexareaType::class, [
                'label' => 'Description',
                'constraints' => [],
                'help' => 'Champ optionel'
            ])
        ;
    }
}
