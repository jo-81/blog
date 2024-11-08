<?php

namespace Blog\Controller\Admin;

use Blog\Core\Abstract\AbstractController;
use Blog\Core\Router\Route;
use Psr\Http\Message\ResponseInterface;

final class UserController extends AbstractController
{
    #[Route("/admin/users", "admin.user.list")]
    public function index(): ResponseInterface
    {
        return $this->render('admin/user/list');
    }

    #[Route("/admin/users/:id", "admin.user.show")]
    public function show(): ResponseInterface
    {
        return $this->render('admin/user/show');
    }
}
