<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Api;

final class ApiErrorDetails
{
    /**
     * @param ?array<string, mixed> $body
     */
    public function __construct(
        private readonly string $type,
        private readonly ?array $body,
        private readonly ?\Exception $exception,
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return ?array<string, mixed>
     */
    public function getBody(): ?array
    {
        return $this->body;
    }

    public function getException(): ?\Exception
    {
        return $this->exception;
    }
}
