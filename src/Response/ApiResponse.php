<?php

namespace App\Response;

use App\Exception\ApiException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse extends Response
{
    /**
     * @param string $content
     * @param int $status
     * @param array<string> $headers
     * @param string $metadata
     */
    public function __construct(string $content = '[]', int $status = 200, array $headers = [], string $metadata = "[]")
    {
        $json = '{"metadata": ' . $metadata . ',"data": ' . $content . '}';
        $headers["Content-Type"] = "application/json";
        parent::__construct($json, $status, $headers);
    }
}
