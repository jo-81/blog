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

    public function index(string $termName): ResponseInterface
    {
        if (! in_array($termName, ['tags', 'categories'], true)) {
            $this->createNotFoundException();
        }

        $formTitle = 'Ajouter';
        $formTitle .= $termName == 'tags' ? ' un tag' : ' une catégorie';
        $entityName = $termName == 'tags' ? Tag::class : Category::class;
        $actionForm = sprintf('/admin/%s/create', $termName);

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
            'form' => $this->getTermFormType(new $entityName(), [
                'attr' => [
                    'action' => $actionForm,
                ],
            ])->createView(),
        ]);
    }

    public function create(string $termName)
    {
        if (! in_array($termName, ['tags', 'categories'], true)) {
            $this->createNotFoundException();
        }

        if ($termName == 'tags') {
            $entity = new Tag();
        } else {
            $entity = new Category();
        }

        $form = $this->getTermFormType($entity);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();
                $data->setSlug(Slugger::slugify($data->getName()));

                $this->em->persist($data);
                $this->em->flush();
                $this->flash->add('success', 'Le term a bien été ajouté.');

            } catch (DatabaseException $e) {
                $this->flash->add('danger', "Le term n'a pas pu être ajouté.");
            } finally {
                return $this->redirect('/admin/' . $termName);
            }
        }

        return $this->redirect('/admin/' . $termName);
    }

    private function getTermFormType(Term $term, array $options = []): FormInterface
    {
        $termName =  strtolower(str_replace('App\\Entity\\', '', get_class($term)));
        if (! in_array($termName, ['tag', 'category'], true)) {
            $this->createNotFoundException();
        }

        $formTypeName = sprintf('App\\Form\\%s\\%sFormType', ucfirst($termName), ucfirst($termName));

        $form = $this->createForm($formTypeName, $term, $options);
        $form->handleRequest($this->request);

        return $form;
    }
}
