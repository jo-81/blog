<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Framework\Http\AbstractController;
use Psr\Http\Message\ResponseInterface;

class DashboardController extends AbstractController
{
    public function dashboard(): ResponseInterface
    {
        return $this->render('admin/dashboard/index.twig');
    }
}
