<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Customer\Payloads;

use YieldTech\SdkPhp\Types\Money;
use YieldTech\SdkPhp\Utils\TypeUtils;

final readonly class CustomerCreditLineInfo
{
    private function __construct(
        public Money $creditLimit,
        public Money $amountAvailable,
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromPayload(array $payload): self
    {
        return new self(
            creditLimit: Money::fromPayload(TypeUtils::expectString($payload['credit_limit'] ?? null)),
            amountAvailable: Money::fromPayload(TypeUtils::expectString($payload['amount_available'] ?? null)),
        );
    }
}
