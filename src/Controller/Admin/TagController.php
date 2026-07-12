<?php

namespace App\Controller\Admin;

use Framework\Http\AbstractController;
use Psr\Http\Message\ResponseInterface;

class TagController extends AbstractController
{
    public function index(): ResponseInterface
    {
        return $this->render('admin/tag/index.twig', [
            'current_page' => 'tags',
        ]);
    }
}