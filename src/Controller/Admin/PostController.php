<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Enums\PostStatus;
use App\Repository\PostRepository;
use Framework\Http\AbstractController;
use Psr\Http\Message\ResponseInterface;
use Framework\Adapters\CyclePaginatorItem;
use Framework\Database\Paginator\Paginator;
use Spiral\Pagination\Paginator as CyclePaginator;

class PostController extends AbstractController
{
    public function __construct(private PostRepository $postRepository) {}

    public function index(): ResponseInterface
    {
        $queryParams = $this->request->getQueryParams();
        $page = isset($queryParams['page']) ? (int) $queryParams['page'] : 1;
        $status = isset($queryParams['post_status']) ? $queryParams['post_status'] : null;

        if (!PostStatus::has($status)) {
            $status = null;
        }

        $wheres = null == $status ? [] : ['status' => $status];
        $select = $this->postRepository->getPosts(['tags', 'category'], ['createdAt' => 'DESC'], $wheres);

        $paginator = new CyclePaginator(Post::PAGINATION, $select->count());
        $paginator = $paginator->withPage($page)->paginate($select);

        $posts = $select->fetchAll();

        $adapter = new CyclePaginatorItem($paginator); /** @phpstan-ignore-line */
        $pagination = new Paginator($adapter);

        return $this->render('admin/post/index.twig', [
            'current_page' => 'posts',
            'posts' => $posts,
            'pagination' => $pagination,
            'posts_status' => $this->postRepository->getStatus(),
            'status' => $status,
        ]);
    }
}
