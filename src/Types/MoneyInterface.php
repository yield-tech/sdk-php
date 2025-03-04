<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Types;

interface MoneyInterface
{
    /** ISO currency code, e.g. "USD", "PHP" */
    public function getCurrencyCode(): string;

    /** The amount, e.g. "1234.50" */
    public function getValue(): string;
}
