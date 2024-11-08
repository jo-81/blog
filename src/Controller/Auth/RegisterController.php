<?php

namespace Blog\Controller\Auth;

use Blog\Core\Abstract\AbstractController;
use Blog\Core\Router\Route;
use Psr\Http\Message\ResponseInterface;

final class RegisterController extends AbstractController
{
    #[Route("/inscription", "user.register")]
    public function register(): ResponseInterface
    {
        return $this->render('auth/register');
    }
}
