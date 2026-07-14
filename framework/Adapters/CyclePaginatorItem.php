<?php

declare(strict_types=1);

namespace Framework\Adapters;

use Spiral\Pagination\Paginator;
use Framework\Database\Paginator\PaginatorItemInterface;

class CyclePaginatorItem implements PaginatorItemInterface
{
    public function __construct(private Paginator $paginator) {}

    public function getCountPages(): int
    {
        return $this->paginator->countPages();
    }

    public function getCountItems(): int
    {
        return $this->paginator->count();
    }

    public function getItemsPerPage(): int
    {
        return $this->paginator->getLimit();
    }

    public function getPage(): int
    {
        return $this->paginator->getPage();
    }
}
