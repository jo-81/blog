<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Entity\Category;
use Framework\Http\AbstractController;
use Psr\Http\Message\ResponseInterface;
use Framework\Adapters\CyclePaginatorItem;
use Framework\Database\Paginator\Paginator;
use Framework\Database\EntityManagerInterface;
use Spiral\Pagination\Paginator as CyclePaginator;

class TermController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) {}

    public function index(string $termName): ResponseInterface
    {
        if (! in_array($termName, ['tags', 'categories'], true)) {
            $this->createNotFoundException();
        }

        $formTitle = 'Ajouter';
        $formTitle .= $termName == 'tags' ? ' un tag' : ' une catégorie';
        $entityName = $termName == 'tags' ? Tag::class : Category::class;

        $queryParams = $this->request->getQueryParams();
        $page = isset($queryParams['page']) ? (int) $queryParams['page'] : 1;

        $repository = $this->em->getRepository($entityName);

        $select = $repository->select()->orderBy('id', 'DESC');

        $paginator = new CyclePaginator(Tag::PAGINATION, $select->count());
        $paginator = $paginator->withPage($page)->paginate($select);

        $terms = $select->fetchAll();

        $adapter = new CyclePaginatorItem($paginator); /** @phpstan-ignore-line */
        $pagination = new Paginator($adapter);

        return $this->render('admin/term/index.twig', [
            'current_page' => $termName,
            'term' => null,
            'form_title' => $formTitle,
            'terms' => $terms,
            'pagination' => $pagination,
            // 'form' => $this->getTagFormType()->createView(),
        ]);
    }
}
