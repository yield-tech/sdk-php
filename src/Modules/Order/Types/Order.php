<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Order\Types;

use YieldTech\SdkPhp\Types\Money;
use YieldTech\SdkPhp\Utils\DateUtils;

final class Order
{
    private function __construct(
        public readonly string $id,
        public readonly string $orderNumber,
        public readonly OrderStatus $status,
        public readonly ?OrderCustomer $customer,
        public readonly \DateTimeImmutable $date,
        public readonly Money $totalAmount,
        public readonly ?string $note,
        public readonly string $paymentLink,
        public readonly \DateTimeImmutable $creationTime,
    ) {
    }

    // @phpstan-ignore missingType.iterableValue
    public static function fromPayload(array $payload): self
    {
        return new self(
            id: $payload['id'],
            orderNumber: $payload['order_number'],
            status: OrderStatus::from($payload['status']),
            customer: $payload['customer'] === null ? null : OrderCustomer::fromPayload($payload['customer']),
            date: DateUtils::fromDatePayload($payload['date']),
            totalAmount: Money::fromPayload($payload['total_amount']),
            note: $payload['note'],
            paymentLink: $payload['payment_link'],
            creationTime: DateUtils::fromTimestampPayload($payload['creation_time']),
        );
    }
}
