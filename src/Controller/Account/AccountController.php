<?php

namespace Blog\Controller\Account;

use Blog\Core\Abstract\AbstractController;
use Blog\Core\Router\Route;
use Psr\Http\Message\ResponseInterface;

final class AccountController extends AbstractController
{
    #[Route("/profil", "user.account")]
    public function profil(): ResponseInterface
    {
        return $this->render('account/profil');
    }

    #[Route("/profil/edit", "user.account.update")]
    public function update(): ResponseInterface
    {
        return $this->render('account/update');
    }

    #[Route("/profil/edit-password", "user.account.update.password")]
    public function updatePassword(): ResponseInterface
    {
        return $this->render('account/update-password');
    }

    #[Route("/profil/comments", "user.account.comment.list")]
    public function comments(): ResponseInterface
    {
        return $this->render('account/comments');
    }

    #[Route("/profil/comments/:id", "user.account.comment.single")]
    public function comment(int $id): ResponseInterface
    {
        return $this->render('account/comment');
    }
}
