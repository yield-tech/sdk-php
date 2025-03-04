<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Order;

use YieldTech\SdkPhp\Api\ApiClient;
use YieldTech\SdkPhp\Api\ApiResult;
use YieldTech\SdkPhp\Modules\Order\Types\Order;
use YieldTech\SdkPhp\Modules\Order\Types\OrderCreateParams;

class OrderClient
{
    public function __construct(
        private readonly ApiClient $api,
    ) {
    }

    /** @return ApiResult<Order> */
    public function fetch(string $id): ApiResult
    {
        return $this->api->runQuery([Order::class, 'fromPayload'], "/order/fetch/{$id}");
    }

    /** @return ApiResult<Order> */
    public function create(OrderCreateParams $params): ApiResult
    {
        $payload = $params->buildPayload();

        return $this->api->runCommand([Order::class, 'fromPayload'], '/order/create', $payload);
    }
}
