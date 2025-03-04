<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Order\Types;

enum OrderStatus: string
{
    case Pending = 'PENDING';
    case Confirmed = 'CONFIRMED';
    case Fulfilled = 'FULFILLED';
    case Cancelled = 'CANCELLED';
}
