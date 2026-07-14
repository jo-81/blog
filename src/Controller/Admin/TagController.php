<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Framework\Adapters\CyclePaginatorItem;
use Framework\Database\Paginator\Paginator;
use Framework\Http\AbstractController;
use Psr\Http\Message\ResponseInterface;
use Spiral\Pagination\Paginator as CyclePaginator;

class TagController extends AbstractController
{
    public function __construct(private TagRepository $tagRepository) {}

    public function index(): ResponseInterface
    {
        $queryParams = $this->request->getQueryParams();
        $page = isset($queryParams['page']) ? (int)$queryParams['page'] : 1;

        $select = $this->tagRepository->select()->orderBy('id', 'DESC');

        $paginator = new CyclePaginator(Tag::PAGINATION, $select->count());
        $paginator = $paginator->withPage($page)->paginate($select);

        $tags = $select->fetchAll();

        $adapter = new CyclePaginatorItem($paginator);
        $pagination = new Paginator($adapter);

        return $this->render('admin/tag/index.twig', [
            'current_page' => 'tags',
            'tags' => $tags,
            'pagination' => $pagination,
        ]);
    }
}
