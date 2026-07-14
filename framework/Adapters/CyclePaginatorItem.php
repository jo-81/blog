<?php

namespace Framework\Adapters;

use Framework\Database\Paginator\PaginatorItemInterface;
use Spiral\Pagination\Paginator;

class CyclePaginatorItem implements PaginatorItemInterface
{
    public function __construct(private Paginator $paginator)
    {}

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