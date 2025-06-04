<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Types;

/**
 * @phpstan-type MoneyLike string|array{string, string}|MoneyInterface
 */
class MoneyPayload
{
    /**
     * @param MoneyLike $money
     */
    public static function build(mixed $money): string
    {
        if (\is_string($money)) {
            return $money;
        } elseif (\is_array($money)) {
            return "{$money[0]} {$money[1]}";
        } else {
            return "{$money->getCurrencyCode()} {$money->getValue()}";
        }
    }
}
