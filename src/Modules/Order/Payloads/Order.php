<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Order\Payloads;

use YieldTech\SdkPhp\Types\Money;
use YieldTech\SdkPhp\Utils\TypeUtils;

final readonly class Order
{
    private function __construct(
        public string $id,
        public string $orderNumber,
        public OrderStatus $status,
        public ?OrderCustomerInfo $customer,
        public \DateTimeImmutable $date,
        public Money $totalAmount,
        public ?string $note,
        public ?string $paymentLink,
        public \DateTimeImmutable $creationTime,
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromPayload(array $payload): self
    {
        return new self(
            id: TypeUtils::expectString($payload['id'] ?? null),
            orderNumber: TypeUtils::expectString($payload['order_number'] ?? null),
            status: OrderStatus::from(TypeUtils::expectString($payload['status'] ?? null)),
            customer: isset($payload['customer']) ? OrderCustomerInfo::fromPayload(TypeUtils::expectRecord($payload['customer'])) : null,
            date: TypeUtils::expectDate($payload['date'] ?? null),
            totalAmount: Money::fromPayload(TypeUtils::expectString($payload['total_amount'] ?? null)),
            note: isset($payload['note']) ? TypeUtils::expectString($payload['note']) : null,
            paymentLink: isset($payload['payment_link']) ? TypeUtils::expectString($payload['payment_link']) : null,
            creationTime: TypeUtils::expectTime($payload['creation_time'] ?? null),
        );
    }
}
