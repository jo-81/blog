<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Service\TagService;
use App\Form\Tag\TagFormType;
use App\Repository\TagRepository;
use Framework\Form\FormInterface;
use Framework\Http\AbstractController;
use Psr\Http\Message\ResponseInterface;
use Framework\Adapters\CyclePaginatorItem;
use Framework\Database\Paginator\Paginator;
use Cycle\Database\Exception\DatabaseException;
use Spiral\Pagination\Paginator as CyclePaginator;

class TagController extends AbstractController
{
    public function __construct(private TagRepository $tagRepository, private TagService $tagService) {}

    public function index(): ResponseInterface
    {
        $queryParams = $this->request->getQueryParams();
        $page = isset($queryParams['page']) ? (int) $queryParams['page'] : 1;

        $select = $this->tagRepository->select()->orderBy('id', 'DESC');

        $paginator = new CyclePaginator(Tag::PAGINATION, $select->count());
        $paginator = $paginator->withPage($page)->paginate($select);

        $tags = $select->fetchAll();

        $adapter = new CyclePaginatorItem($paginator); /** @phpstan-ignore-line */
        $pagination = new Paginator($adapter);

        return $this->render('admin/tag/index.twig', [
            'current_page' => 'tags',
            'tags' => $tags,
            'pagination' => $pagination,
            'form' => $this->getTagFormType()->createView(),
        ]);
    }

    public function create()
    {
        $form = $this->getTagFormType(new Tag());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();
                $this->tagService->create($data);
                $this->flash->add('success', 'Le tag a bien été ajouté.');

            } catch (DatabaseException $e) {
                $this->flash->add('danger', "Le tag n'a pas pu être ajouté.");
            } finally {
                return $this->redirect('/admin/tags');
            }
        }

        return $this->redirect('/admin/tags');
    }

    private function getTagFormType(?Tag $tag = null): FormInterface
    {
        $form = $this->createForm(TagFormType::class, $tag);
        $form->handleRequest($this->request);

        return $form;
    }
}
