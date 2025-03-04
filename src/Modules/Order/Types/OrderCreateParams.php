<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Order\Types;

use YieldTech\SdkPhp\Types\Money;
use YieldTech\SdkPhp\Types\MoneyInterface;
use YieldTech\SdkPhp\Utils\DateUtils;

final class OrderCreateParams
{
    private function __construct(
        public readonly string $customerId,
        public readonly \DateTimeInterface $date,
        public readonly MoneyInterface $totalAmount,
        public readonly ?string $note,
        // public readonly ?OrderReturnAction $returnAction,
    ) {
    }

    /**
     * @param array{
     *   customer_id: string,
     *   date: \DateTimeInterface,
     *   total_amount: MoneyInterface,
     *   note?: ?string,
     * } $params
     */
    public static function from(array $params): self
    {
        return new self(
            customerId: $params['customer_id'],
            date: $params['date'],
            totalAmount: $params['total_amount'],
            note: $params['note'] ?? null,
        );
    }

    public function buildPayload(): mixed
    {
        return [
            'customer_id' => $this->customerId,
            'date' => DateUtils::buildDatePayload($this->date),
            'total_amount' => Money::buildPayload($this->totalAmount),
            'note' => $this->note,
            // 'return_action' => $this->returnAction?->buildPayload(),
        ];
    }
}
