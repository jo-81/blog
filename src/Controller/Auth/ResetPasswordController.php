<?php

namespace Blog\Controller\Auth;

use Blog\Core\Abstract\AbstractController;
use Blog\Core\Router\Route;
use Psr\Http\Message\ResponseInterface;

final class ResetPasswordController extends AbstractController
{
    #[Route("/demande-modification-mot-de-passe", "app.ask.reset.password")]
    public function askResetPassword(): ResponseInterface
    {
        return $this->render('auth/ask-reset-password');
    }

    #[Route("/modification-mot-de-passe", "app.reset.password")]
    public function resetPassword(): ResponseInterface
    {
        return $this->render('auth/reset-password');
    }
}
