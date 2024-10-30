<?php

namespace App\Exception;

class ApiException extends \Exception implements ApiExceptionInterface
{
    /**
     * @param array<string> $errorMessages
     * @param int $statusCode
     * @param array<string, string> $headers
     */
    public function __construct(
        private readonly array $errorMessages,
        private readonly int $statusCode,
        private readonly array $headers = []
    ) {
        $nativeError  = "ApiException thrown with the following errors:\n";
        $nativeError .= json_encode($this->errorMessages);
        parent::__construct(
            message: $nativeError
        );
    }

    /**
     * @return array<string>
     */
    public function getErrorMessages(): array
    {
        return $this->errorMessages;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array<string>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
