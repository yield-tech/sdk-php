<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Types;

class Money implements MoneyInterface
{
    public function __construct(
        public readonly string $currencyCode,
        public readonly string $value,
    ) {
        // TODO: Validate
    }

    // @phpstan-ignore missingType.iterableValue
    public static function fromPayload(array $payload): self
    {
        return new self(
            $payload['currency_code'],
            $payload['value'],
        );
    }

    public static function buildPayload(MoneyInterface $money): mixed
    {
        return [
            'currency_code' => $money->getCurrencyCode(),
            'value' => $money->getValue(),
        ];
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
