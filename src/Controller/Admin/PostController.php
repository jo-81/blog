<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Framework\Http\AbstractController;
use Psr\Http\Message\ResponseInterface;

class PostController extends AbstractController
{
    public function index(): ResponseInterface
    {
        return $this->render('admin/post/index.twig', [
            'current_page' => 'posts',
        ]);
    }
}
