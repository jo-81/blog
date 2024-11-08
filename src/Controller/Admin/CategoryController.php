<?php

namespace Blog\Controller\Admin;

use Blog\Core\Abstract\AbstractController;
use Blog\Core\Router\Route;
use Psr\Http\Message\ResponseInterface;

final class CategoryController extends AbstractController
{
    #[Route("/admin/categories", "admin.category.list")]
    public function index(): ResponseInterface
    {
        return $this->render('admin/category/list');
    }

    #[Route("/admin/categories/add", "admin.category.create")]
    public function create(): ResponseInterface
    {
        return $this->render('admin/category/add');
    }

    #[Route("/admin/categories/:id/edit", "admin.category.update")]
    public function update(): ResponseInterface
    {
        return $this->render('admin/category/edit');
    }
}
