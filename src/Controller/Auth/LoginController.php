<?php

namespace Blog\Controller\Auth;

use Blog\Core\Abstract\AbstractController;
use Blog\Core\Router\Route;
use Blog\Form\Auth\LoginFormType;
use Psr\Http\Message\ResponseInterface;

final class LoginController extends AbstractController
{
    #[Route("/connexion", "app.login")]
    public function login(): ResponseInterface
    {
        $form = $this->createForm(LoginFormType::class);

        return $this->render('auth/login', ['form' => $form]);
    }
}
