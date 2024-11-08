<?php

namespace Blog\Controller\Admin;

use Blog\Core\Abstract\AbstractController;
use Blog\Core\Router\Route;
use Psr\Http\Message\ResponseInterface;

final class TagController extends AbstractController
{
    #[Route("/admin/tags", "admin.tag.list")]
    public function index(): ResponseInterface
    {
        return $this->render('admin/tag/list');
    }

    #[Route("/admin/tags/add", "admin.tag.create")]
    public function create(): ResponseInterface
    {
        return $this->render('admin/tag/add');
    }

    #[Route("/admin/tags/:id/edit", "admin.tag.update")]
    public function update(): ResponseInterface
    {
        return $this->render('admin/tag/edit');
    }
}
