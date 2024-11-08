<?php

namespace Blog\Controller\Front;

use Blog\Core\Abstract\AbstractController;
use Blog\Core\Router\Route;
use Psr\Http\Message\ResponseInterface;

final class ArticleController extends AbstractController
{
    #[Route("/articles/:slug", "article.show")]
    public function show(string $slug): ResponseInterface
    {
        return $this->render("front/article/single", ['slug' => $slug]);
    }
}
