<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Customer\Types;

final class Customer
{
    private function __construct(
        public readonly string $id,
        public readonly string $registeredName,
        public readonly ?string $tradeName,
        public readonly ?CustomerCreditLine $creditLine,
    ) {
    }

    // @phpstan-ignore missingType.iterableValue
    public static function fromPayload(array $payload): self
    {
        return new self(
            id: $payload['id'],
            registeredName: $payload['registered_name'],
            tradeName: $payload['trade_name'],
            creditLine: $payload['credit_line'] === null ? null : CustomerCreditLine::fromPayload($payload['credit_line']),
        );
    }
}
