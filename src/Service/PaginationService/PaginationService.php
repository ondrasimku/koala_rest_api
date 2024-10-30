<?php

namespace App\Service\PaginationService;

use App\Exception\InvalidItemsPerPageException;
use App\Exception\InvalidPageNumberException;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

/**
 * @template T
 */
class PaginationService
{
    private const DEFAULT_PAGE = 1;
    private const DEFAULT_ITEMS_PER_PAGE = 10;
    private const MAX_ITEMS_PER_PAGE = 100; // Define a max limit for items per page to avoid excessive load

    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    /**
     * Paginate a Doctrine query and return a paginated result with data already fetched.
     *
     * @param Query $query The Doctrine query to paginate.
     * @return PaginatedResult<T> The paginated result with fetched data.
     * @throws InvalidPageNumberException
     * @throws InvalidItemsPerPageException
     */
    public function paginate(Query $query): PaginatedResult
    {
        $request = $this->requestStack->getCurrentRequest();

        // Get page and items per page from query parameters or use default values
        $page = (int) $request?->query->get('page', (string)self::DEFAULT_PAGE);
        $itemsPerPage = (int) $request?->query->get('itemsPerPage', (string)self::DEFAULT_ITEMS_PER_PAGE);

        // Validate page and itemsPerPage
        if ($page < 1) {
            throw new InvalidPageNumberException(
                ['Page number must be a positive integer.'],
                Response::HTTP_BAD_REQUEST
            );
        }

        if ($itemsPerPage < 1 || $itemsPerPage > self::MAX_ITEMS_PER_PAGE) {
            throw new InvalidItemsPerPageException(
                ['Items per page must be between 1 and ' . self::MAX_ITEMS_PER_PAGE],
                Response::HTTP_BAD_REQUEST
            );
        }

        $query->setFirstResult($itemsPerPage * ($page - 1)); // Set offset
        $query->setMaxResults($itemsPerPage); // Set limit

        // Use Doctrine's Paginator to handle the pagination and count results
        $paginator = new Paginator($query);

        // Get all results as an array
        $data = iterator_to_array($paginator);

        // Count results for pagination metadata
        $resultsCount = count($paginator);
        $totalPages = (int)ceil($resultsCount / $itemsPerPage);

        // Create and return a PaginatedResult with fetched data and metadata
        $paginationMetadata = new PaginationResultMetadata(
            $resultsCount,
            $page,
            $itemsPerPage,
            $totalPages
        );

        return new PaginatedResult($data, $paginationMetadata);
    }
}
