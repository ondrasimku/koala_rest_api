<?php

namespace App\Service\PaginationService;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;

/**
 * @template T
 */
class PaginatedResult
{
    /**
     * @var array<T>
     */
    private array $data;
    private PaginationResultMetadata $metadata;

    /**
     * @param array<T> $data
     * @param PaginationResultMetadata $metadata
     */
    public function __construct(array $data, PaginationResultMetadata $metadata)
    {
        $this->data = $data;
        $this->metadata = $metadata;
    }

    /**
     * @return array<T>
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function getMetadata(): PaginationResultMetadata
    {
        return $this->metadata;
    }
}
