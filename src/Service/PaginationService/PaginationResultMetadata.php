<?php

namespace App\Service\PaginationService;

class PaginationResultMetadata
{
    private int $totalCount;
    private int $currentPage;
    private int $itemsPerPage;
    private int $totalPages;

    public function __construct(int $totalCount, int $currentPage, int $limit, int $totalPages)
    {
        $this->totalCount = $totalCount;
        $this->currentPage = $currentPage;
        $this->itemsPerPage = $limit;
        $this->totalPages = $totalPages;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }
}
