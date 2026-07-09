<?php

declare(strict_types=1);

namespace App\Form\Auth;

use Framework\Form\Field\EmailType;
use Framework\Form\Field\PasswordType;
use Framework\Form\FormBuilderInterface;
use Framework\Validation\Constraint\Email;
use Framework\Validation\Constraint\NotBlank;

class LoginFormType
{
    public function buildForm(FormBuilderInterface $builder): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'placeholder' => 'votre@email.com',
                'autocomplete' => 'email',
                'constraints' => [
                    new NotBlank(['message' => "L'adresse email est requise."]),
                    new Email(['message' => "Le format de l'email est invalide."]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'required' => true,
                'placeholder' => '*******',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
        ;
    }
}
