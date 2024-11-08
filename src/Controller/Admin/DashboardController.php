<?php

namespace Blog\Controller\Admin;

use Blog\Core\Abstract\AbstractController;
use Blog\Core\Router\Route;
use Psr\Http\Message\ResponseInterface;

final class DashboardController extends AbstractController
{
    #[Route("/admin", "dashboard")]
    public function dashboard(): ResponseInterface
    {
        return $this->render('admin/dashboard');
    }
}
