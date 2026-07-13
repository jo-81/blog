<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Tag;
use Spiral\Pagination\Paginator;
use App\Repository\TagRepository;
use Framework\Http\AbstractController;
use Psr\Http\Message\ResponseInterface;

class TagController extends AbstractController
{
    public function __construct(private TagRepository $tagRepository) {}

    public function index(): ResponseInterface
    {
        $select = $this->tagRepository->select();
        $select->orderBy('id', 'DESC');

        $paginator = new Paginator(Tag::PAGINATION);
        $paginator->paginate($select);

        return $this->render('admin/tag/index.twig', [
            'current_page' => 'tags',
            'tags' => $select->fetchAll(),
        ]);
    }
}
