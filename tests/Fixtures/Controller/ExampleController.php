<?php

namespace App\Tests\Fixtures\Controller;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class ExampleController
{
    public function index(): ResponseInterface
    {
        return new Response();
    }

    public function postShow(int $id): ResponseInterface
    {
        return new Response(200, [], "Post $id");
    }

    public function postList(): ResponseInterface
    {
        return new Response(200, [], 'Liste des articles');
    }
}
