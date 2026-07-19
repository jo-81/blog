<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Entity\Term;
use App\Entity\Category;
use Framework\Utils\Slugger;
use Framework\Form\FormInterface;
use Framework\Http\AbstractController;
use Psr\Http\Message\ResponseInterface;
use Framework\Adapters\CyclePaginatorItem;
use Framework\Database\Paginator\Paginator;
use Framework\Database\EntityManagerInterface;
use Cycle\Database\Exception\DatabaseException;
use Spiral\Pagination\Paginator as CyclePaginator;

class TermController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) {}

    public function index(string $termName, ?int $id = null): ResponseInterface
    {
        if (! in_array($termName, ['tags', 'categories'], true)) {
            $this->createNotFoundException();
        }

        $term = null;
        $entityName = $termName == 'tags' ? Tag::class : Category::class;
        $repository = $this->em->getRepository($entityName);

        if (!is_null($id)) {
            $term = $repository->findByPK($id);
            if (is_null($term)) {
                $this->createNotFoundException();
            }
        }

        $formTitle = is_null($id) ? 'Ajouter' : 'Modifier';
        $formTitle .= $termName == 'tags' ? ' un tag' : ' une catégorie';
        $actionForm = is_null($id) ? sprintf('/admin/%s/create', $termName) : sprintf('/admin/%s/create/%d', $termName, $id);

        $queryParams = $this->request->getQueryParams();
        $page = isset($queryParams['page']) ? (int) $queryParams['page'] : 1;

        $select = $repository->select()->orderBy('id', 'DESC');

        $paginator = new CyclePaginator(Tag::PAGINATION, $select->count());
        $paginator = $paginator->withPage($page)->paginate($select);

        $terms = $select->fetchAll();

        $adapter = new CyclePaginatorItem($paginator); /** @phpstan-ignore-line */
        $pagination = new Paginator($adapter);

        return $this->render('admin/term/index.twig', [
            'current_page' => $termName,
            'term' => $term,
            'form_title' => $formTitle,
            'terms' => $terms,
            'pagination' => $pagination,
            'form' => $this->getTermFormType(is_null($term) ? new $entityName() : $term, [
                'attr' => [
                    'action' => $actionForm,
                ],
            ])->createView(),
        ]);
    }

    public function create(string $termName, ?int $id = null): ResponseInterface
    {
        if (! in_array($termName, ['tags', 'categories'], true)) {
            $this->createNotFoundException();
        }

        $actionName = 'ajouté';
        if ($termName == 'tags') {
            $entity = new Tag();
        } else {
            $entity = new Category();

        }

        if ($id) {
            $entity = $this->em->getRepository(get_class($entity))->findByPK($id);
            $actionName = 'modifié';

            if (is_null($entity)) {
                $this->createNotFoundException();
            }
        }

        $form = $this->getTermFormType($entity);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();
                $data->setSlug(Slugger::slugify($data->getName()));

                $this->em->persist($data);
                $this->em->flush();
                $this->flash->add('success', sprintf('Le term a bien été %s.', $actionName));

            } catch (DatabaseException $e) {
                $this->flash->add('danger', sprintf("Le term n'a pas pu être %s.", $actionName));
            } finally {
                return $this->redirect('/admin/' . $termName);
            }
        }

        return $this->redirect('/admin/' . $termName);
    }

    private function getTermFormType(Term $term, array $options = []): FormInterface
    {
        $termName = null;
        if (str_contains(get_class($term), 'Tag')) {
            $termName = 'tag';
        }

        if (str_contains(get_class($term), 'Category')) {
            $termName = 'category';
        }

        if (! in_array($termName, ['tag', 'category'], true)) {
            $this->createNotFoundException();
        }

        $formTypeName = sprintf('App\\Form\\%s\\%sFormType', ucfirst($termName), ucfirst($termName));

        $form = $this->createForm($formTypeName, $term, $options);
        $form->handleRequest($this->request);

        return $form;
    }
}
