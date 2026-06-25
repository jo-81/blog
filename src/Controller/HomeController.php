<?php

declare(strict_types=1);

namespace App\Controller;

use Framework\Http\AbstractController;
use Psr\Http\Message\ResponseInterface;

class HomeController extends AbstractController
{
    public function index(): ResponseInterface
    {
        return $this->render('/home/index.twig');
    }
}
