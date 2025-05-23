<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Order;

use YieldTech\SdkPhp\Modules\Order\Payloads\Order;
use YieldTech\SdkPhp\Modules\Order\Payloads\OrderCreatePayload;

/**
 * @phpstan-import-type OrderCreateParams from OrderCreatePayload
 */
class OrderClient
{
    public function __construct(
        private readonly OrderBaseClient $base,
    ) {
    }

    public function fetch(string $id): Order
    {
        return $this->base->fetch($id)->getData();
    }

    /**
     * @param OrderCreateParams $params
     */
    public function create(array $params): Order
    {
        return $this->base->create($params)->getData();
    }
}
