<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Api;

// TODO: Make this abstract once we properly handle the different error types
class ApiErrorDetails
{
    private readonly ?int $statusCode;
    private readonly ?string $requestId;

    public function __construct(
        ?int $statusCode,
        ?string $requestId,
    ) {
        $this->statusCode = $statusCode;
        $this->requestId = $requestId;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function getRequestId(): ?string
    {
        return $this->requestId;
    }
}
