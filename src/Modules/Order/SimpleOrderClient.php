<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Order;

use YieldTech\SdkPhp\Api\ApiClient;
use YieldTech\SdkPhp\Modules\Order\Types\Order;
use YieldTech\SdkPhp\Modules\Order\Types\OrderCreateParams;

class SimpleOrderClient
{
    private readonly OrderClient $innerClient;

    public function __construct(
        ApiClient $api,
    ) {
        $this->innerClient = new OrderClient($api);
    }

    public function fetch(string $id): Order
    {
        return $this->innerClient->fetch($id)->getData();
    }

    public function create(OrderCreateParams $params): Order
    {
        return $this->innerClient->create($params)->getData();
    }
}
