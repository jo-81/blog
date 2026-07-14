<?php

namespace Framework\Database\Paginator;

interface PaginatorItemInterface
{
    public function getCountPages(): int;

    public function getCountItems(): int;

    public function getItemsPerPage(): int;

    public function getPage(): int;
}