<?php

namespace Framework\Renderer\TwigExtensions;

use Framework\Database\Paginator\Paginator;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PaginationExtension extends AbstractExtension
{
    public function __construct(private ServerRequestInterface $request) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('render_pagination', [$this, 'renderPagination'], [
                'needs_environment' => true,
                'is_safe' => ['html']
            ]),
            new TwigFunction('display_pagination_info', [$this, 'renderDisplayInfo'], [
                'is_safe' => ['html']
            ]),
        ];
    }

    public function renderPagination(Environment $twig, Paginator $paginator, string $template = 'admin/partials/_pagination.twig'): string
    {
        if ($paginator->getTotalPages() <= 1) {
            return '';
        }

        $currentPath = $this->request->getUri()->getPath();
        $queryParams = $this->request->getQueryParams();

        return $twig->render($template, [
            'paginator'    => $paginator,
            'current_path' => $currentPath,
            'query_params' => $queryParams
        ]);
    }

    public function renderDisplayInfo(Paginator $paginator): string
    {
        // dump($paginator);

        $currentPage = $paginator->getCurrentPage();

        $start = 1 * (($currentPage - 1 ) * $paginator->getItemsPerPage() + 1);

        $end = $currentPage == $paginator->getTotalPages() 
            ? $paginator->getTotalItems()
            : $paginator->getItemsPerPage() * $currentPage
        ;

        return sprintf('Affichage de %d–%d sur %s tags', $start, $end, $paginator->getTotalItems());
    }
}