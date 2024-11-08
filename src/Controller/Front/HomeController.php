<?php

namespace Blog\Controller\Front;

use Blog\Core\Abstract\AbstractController;
use Blog\Core\Router\Route;
use Psr\Http\Message\ResponseInterface;

final class HomeController extends AbstractController
{
    #[Route('/', 'homepage')]
    public function index(): ResponseInterface
    {
        return $this->render('front/homepage');
    }
}
