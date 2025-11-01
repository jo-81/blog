<?php

declare(strict_types=1);

namespace Blog\HomeModule\Controller;

use Framework\Abstract\AbstractController;
use Framework\Http\Interface\AppResponseInterface;

class HomeController extends AbstractController
{
    public function index(): AppResponseInterface
    {
        return $this->render("homepage.html.twig");
    }
}
