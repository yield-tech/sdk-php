<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Customer\Types;

use YieldTech\SdkPhp\Types\Money;
use YieldTech\SdkPhp\Types\MoneyInterface;

final class CustomerCreditLine
{
    private function __construct(
        public readonly MoneyInterface $creditLimit,
        public readonly MoneyInterface $creditAvailable,
    ) {
    }

    // @phpstan-ignore missingType.iterableValue
    public static function fromPayload(array $payload): self
    {
        return new self(
            creditLimit: Money::fromPayload($payload['credit_limit']),
            creditAvailable: Money::fromPayload($payload['credit_available']),
        );
    }
}
