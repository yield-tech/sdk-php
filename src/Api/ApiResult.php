<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Api;

/**
 * @template T
 */
final class ApiResult
{
    private readonly ?string $requestId;

    /** @var ?T */
    private readonly mixed $data;
    private readonly ?ApiErrorDetails $error;

    /** @param ?T $data */
    private function __construct(
        ?string $requestId,
        mixed $data,
        ?ApiErrorDetails $error,
    ) {
        $this->requestId = $requestId;
        $this->data = $data;
        $this->error = $error;
    }

    /**
     * @template U
     *
     * @param U                            $data
     * @param array{ request_id?: string } $options
     *
     * @return ApiResult<U>
     */
    public static function createSuccess(
        mixed $data,
        array $options = [],
    ): self {
        return new self(
            $options['request_id'] ?? null,
            $data,
            null,
        );
    }

    /**
     * @param array{ request_id?: string } $options
     *
     * @return ApiResult<*>
     */
    public static function createFailure(
        ApiErrorDetails $error,
        array $options = [],
    ): self {
        return new self(
            $options['request_id'] ?? null,
            null,
            $error,
        );
    }

    public function isOk(): bool
    {
        return $this->error === null;
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
            throw new ApiException($this->error);
        }

        // @phpstan-ignore return.type
        return $this->data;
    }

    public function getError(): ?ApiErrorDetails
    {
        return $this->error;
    }
}
