<?php

namespace Blog\Controller\Admin;

use Blog\Core\Abstract\AbstractController;
use Blog\Core\Router\Route;
use Psr\Http\Message\ResponseInterface;

final class CommentController extends AbstractController
{
    #[Route("/admin/comments", "admin.comment.list")]
    public function index(): ResponseInterface
    {
        return $this->render('admin/comment/list');
    }

    #[Route("/admin/comments/:id", "admin.comment.show")]
    public function show(): ResponseInterface
    {
        return $this->render('admin/comment/show');
    }
}
