<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Order\Payloads;

use YieldTech\SdkPhp\Types\MoneyPayload;

/**
 * @phpstan-import-type IntoMoneyPayload from MoneyPayload
 *
 * @phpstan-type OrderCreateParams array{
 *   customer_id: string,
 *   total_amount: IntoMoneyPayload,
 *   note?: ?String,
 * }
 */
class OrderCreatePayload
{
    /**
     * @param OrderCreateParams $params
     *
     * @phpstan-ignore missingType.iterableValue
     */
    public static function build(array $params): array
    {
        return [
            'customer_id' => $params['customer_id'],
            'total_amount' => MoneyPayload::build($params['total_amount']),
            'note' => $params['note'] ?? null,
        ];
    }
}
