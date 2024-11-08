<?php

namespace Blog\Controller\Admin;

use Blog\Core\Abstract\AbstractController;
use Blog\Core\Router\Route;
use Psr\Http\Message\ResponseInterface;

final class ArticleController extends AbstractController
{
    #[Route("/admin/articles", "admin.article.list")]
    public function index(): ResponseInterface
    {
        return $this->render('admin/article/list');
    }

    #[Route("/admin/articles/add", "admin.article.create")]
    public function create(): ResponseInterface
    {
        return $this->render('admin/article/add');
    }

    #[Route("/admin/articles/:id/edit", "admin.article.update")]
    public function update(): ResponseInterface
    {
        return $this->render('admin/article/edit');
    }
}
