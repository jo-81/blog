<?php

namespace App\Controller\Admin;

use Framework\Http\AbstractController;
use Psr\Http\Message\ResponseInterface;

class CategoryController extends AbstractController
{
    public function index(): ResponseInterface
    {
        return $this->render('admin/category/index.twig', [
            'current_page' => 'categories',
        ]);
    }
}