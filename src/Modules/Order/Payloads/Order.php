<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Order\Payloads;

use YieldTech\SdkPhp\Types\Money;
use YieldTech\SdkPhp\Utils\AssertUtils;

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
            id: AssertUtils::assertString($payload['id'] ?? null),
            orderNumber: AssertUtils::assertString($payload['order_number'] ?? null),
            status: OrderStatus::from(AssertUtils::assertString($payload['status'] ?? null)),
            customer: isset($payload['customer']) ? OrderCustomerInfo::fromPayload(AssertUtils::assertAssociativeArray($payload['customer'])) : null,
            date: AssertUtils::assertDate($payload['date'] ?? null),
            totalAmount: Money::fromPayload(AssertUtils::assertString($payload['total_amount'] ?? null)),
            note: isset($payload['note']) ? AssertUtils::assertString($payload['note']) : null,
            paymentLink: isset($payload['payment_link']) ? AssertUtils::assertString($payload['payment_link']) : null,
            creationTime: AssertUtils::assertTime($payload['creation_time'] ?? null),
        );
    }
}
