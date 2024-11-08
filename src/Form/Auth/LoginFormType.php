<?php

namespace Blog\Form\Auth;

use Blog\Core\Form\AbstractFormType;
use Blog\Core\Form\Types\PasswordType;
use Blog\Core\Form\Types\TextType;

final class LoginFormType extends AbstractFormType
{
    /**
     * getfields
     *
     * @return array<mixed>
     */
    public function getfields(): array
    {
        return [
            'username' => [
                'type' => TextType::class,
                'label' => 'Pseudo',
            ],
            'password' => [
                'type' => PasswordType::class,
                'label' => 'Mot de passe',
            ],
        ];
    }
}
