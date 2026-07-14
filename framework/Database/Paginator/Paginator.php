<?php

namespace Framework\Database\Paginator;

use Framework\Database\Paginator\PaginatorItemInterface;

class Paginator
{
    private int $totalPages;

    private int $totalItems = 0;

    private int $itemsPerPage;

    private int $currentPage;

    public function __construct(private PaginatorItemInterface $cyclePaginator) {
        $this->totalPages = (int) $this->cyclePaginator->getCountPages();
        $this->totalItems = (int) $this->cyclePaginator->getCountItems();
        $this->itemsPerPage = (int) $this->cyclePaginator->getItemsPerPage();
        $this->currentPage = max(1, $this->cyclePaginator->getPage());
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    public function hasPreviousPage(): bool
    {
        return $this->currentPage > 1;
    }

    public function getPreviousPage(): int
    {
        return $this->currentPage - 1;
    }

    public function hasNextPage(): bool
    {
        return $this->currentPage < $this->totalPages;
    }

    public function getNextPage(): int
    {
        return $this->currentPage + 1;
    }

    /**
     * Optionnel : Génère une liste de numéros de page intelligente 
     * (ex: [1, 2, 3, '...', 10]) pour éviter d'afficher 50 boutons.
     */
    public function getPageRange(int $onEachSide = 2): array
    {
        if ($this->totalPages <= 1) {
            return [];
        }

        $pages = [];
        for ($i = 1; $i <= $this->totalPages; $i++) {
            if ($i == 1 || $i == $this->totalPages || abs($i - $this->currentPage) <= $onEachSide) {
                $pages[] = $i;
            } elseif (end($pages) !== '...') {
                $pages[] = '...';
            }
        }
        return $pages;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }
}