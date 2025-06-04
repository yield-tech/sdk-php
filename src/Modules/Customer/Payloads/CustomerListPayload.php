<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Customer\Payloads;

use YieldTech\SdkPhp\Types\CursorPayload;

/**
 * @phpstan-import-type CursorLike from CursorPayload
 *
 * @phpstan-type CustomerListParams array{
 *   limit?: ?int,
 *   after?: ?CursorLike,
 *   customer_code?: ?string,
 *   extra_system_id?: ?string,
 * }
 */
class CustomerListPayload
{
    /**
     * @param CustomerListParams $params
     *
     * @return array<string, string|int|null>
     */
    public static function build(array $params): array
    {
        return [
            'limit' => $params['limit'] ?? null,
            'after' => isset($params['after']) ? CursorPayload::build($params['after']) : null,
            'customer_code' => $params['customer_code'] ?? null,
            'extra_system_id' => $params['extra_system_id'] ?? null,
        ];
    }
}
