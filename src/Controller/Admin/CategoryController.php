<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Framework\Http\AbstractController;
use Psr\Http\Message\ResponseInterface;
use Framework\Adapters\CyclePaginatorItem;
use Framework\Database\Paginator\Paginator;
use Spiral\Pagination\Paginator as CyclePaginator;

class CategoryController extends AbstractController
{
    public function __construct(private CategoryRepository $categoryRepository) {}

    public function index(): ResponseInterface
    {
        $queryParams = $this->request->getQueryParams();
        $page = isset($queryParams['page']) ? (int) $queryParams['page'] : 1;

        $select = $this->categoryRepository->select()->orderBy('id', 'DESC');

        $paginator = new CyclePaginator(Category::PAGINATION, $select->count());
        $paginator = $paginator->withPage($page)->paginate($select);

        $categories = $select->fetchAll();

        $adapter = new CyclePaginatorItem($paginator); /** @phpstan-ignore-line */
        $pagination = new Paginator($adapter);

        return $this->render('admin/category/index.twig', [
            'current_page' => 'categories',
            'pagination' => $pagination,
            'categories' => $categories,
        ]);
    }
}
