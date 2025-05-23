<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Types;

/**
 * @phpstan-type IntoMoneyPayload string|MoneyInterface
 */
class MoneyPayload
{
    /**
     * @param IntoMoneyPayload $money
     */
    public static function build(mixed $money): string
    {
        if (\is_string($money)) {
            return $money;
        }

        return "{$money->getCurrencyCode()} {$money->getValue()}";
    }
}
