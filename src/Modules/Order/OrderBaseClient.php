<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Order;

use YieldTech\SdkPhp\Api\ApiClient;
use YieldTech\SdkPhp\Api\ApiResult;
use YieldTech\SdkPhp\Modules\Order\Payloads\Order;
use YieldTech\SdkPhp\Modules\Order\Payloads\OrderCreatePayload;

/**
 * @phpstan-import-type OrderCreateParams from OrderCreatePayload
 */
class OrderBaseClient
{
    public function __construct(
        private readonly ApiClient $api,
    ) {
    }

    /**
     * @return ApiResult<Order>
     */
    public function fetch(string $id): ApiResult
    {
        $encodedId = rawurlencode($id);
        $response = $this->api->runQuery("/order/fetch/{$encodedId}");

        return ApiClient::processResponse($response, [Order::class, 'fromPayload']);
    }

    /**
     * @param OrderCreateParams $params
     *
     * @return ApiResult<Order>
     */
    public function create(array $params): ApiResult
    {
        $payload = OrderCreatePayload::build($params);
        $response = $this->api->runCommand('/order/create', $payload);

        return ApiClient::processResponse($response, [Order::class, 'fromPayload']);
    }
}
