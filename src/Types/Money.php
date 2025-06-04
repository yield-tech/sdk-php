<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Types;

class Money implements MoneyInterface
{
    public function __construct(
        private readonly string $currencyCode,
        private readonly string $value,
    ) {
    }

    public static function fromPayload(string $payload): self
    {
        if (!preg_match('/^([A-Z]{3}) (-?\d+(?:\.\d+)?)$/', $payload, $m)) {
            throw new \InvalidArgumentException("Invalid money: \"{$payload}\"");
        }

        return new self(currencyCode: $m[1], value: $m[2]);
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
