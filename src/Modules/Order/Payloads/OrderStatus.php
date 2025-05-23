<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Order\Payloads;

enum OrderStatus: string
{
    case PENDING = 'PENDING';
    case CONFIRMED = 'CONFIRMED';
    case FULFILLED = 'FULFILLED';
    case CANCELLED = 'CANCELLED';
}
