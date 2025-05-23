<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Api;

/**
 * @template T
 */
final class ApiResult
{
    /** @param ?T $data */
    private function __construct(
        private readonly int $statusCode,
        private readonly ?string $requestId,
        private readonly mixed $data,
        private readonly ?ApiErrorDetails $error,
    ) {
    }

    /**
     * @template U
     *
     * @param U $data
     *
     * @return ApiResult<U>
     */
    public static function createSuccess(
        int $statusCode,
        ?string $requestId,
        mixed $data,
    ): self {
        return new self(
            $statusCode,
            $requestId,
            $data,
            null,
        );
    }

    /**
     * @param ?array<string, mixed> $errorBody
     *
     * @return ApiResult<*>
     */
    public static function createFailure(
        int $statusCode,
        ?string $requestId,
        string $errorType,
        ?array $errorBody,
    ): self {
        $error = new ApiErrorDetails($errorType, $errorBody);

        return new self(
            $statusCode,
            $requestId,
            null,
            $error,
        );
    }

    public function isOk(): bool
    {
        return $this->error === null;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    /**
     * @return T
     */
    public function getData(): mixed
    {
        if ($this->error !== null) {
            throw new ApiException($this);
        }

        // @phpstan-ignore return.type
        return $this->data;
    }

    public function getError(): ?ApiErrorDetails
    {
        return $this->error;
    }
}
