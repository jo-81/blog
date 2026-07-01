<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use Framework\Http\AbstractController;
use Psr\Http\Message\ResponseInterface;

class LoginController extends AbstractController
{
    public function login(): ResponseInterface
    {
        if ($this->request->getMethod() == 'POST') {

            return $this->redirect('/');
        }

        return $this->render('/auth/login.twig');
    }
}
