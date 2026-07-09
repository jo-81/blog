<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Form\Auth\LoginFormType;
use Framework\Http\AbstractController;
use Psr\Http\Message\ResponseInterface;
use Framework\Security\Auth\Authentication;

class LoginController extends AbstractController
{
    public function __construct(private Authentication $auth) {}

    public function login(): ResponseInterface
    {
        if ($this->auth->check()) {
            return $this->redirect('/');
        }

        $form = $this->createForm(LoginFormType::class);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $success = $this->auth->attempt($data['email'], $data['password']);

            if ($success) {
                $this->flash->add('success', 'Ravi de vous revoir !');

                return $this->redirect('/');
            }

            $this->flash->add('error', 'Identifiants invalides ou compte inexistant.');

            return $this->redirect('/connexion');
        }

        return $this->render('/auth/login.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function logout(): ResponseInterface
    {
        $this->auth->logout();

        return $this->redirect('/connexion');
    }
}
