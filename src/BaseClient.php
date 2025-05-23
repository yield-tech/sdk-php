<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp;

use YieldTech\SdkPhp\Api\ApiClient;
use YieldTech\SdkPhp\Modules\Order\OrderBaseClient;
use YieldTech\SdkPhp\Modules\Self\SelfBaseClient;

/**
 * @phpstan-import-type ClientOptions from ApiClient
 */
class BaseClient
{
    public readonly ApiClient $api;

    public readonly SelfBaseClient $self;
    public readonly OrderBaseClient $order;

    /**
     * @param ClientOptions $options
     */
    public function __construct(string $apiKey, array $options = [])
    {
        $this->api = new ApiClient($apiKey, $options);

        $this->self = new SelfBaseClient($this->api);
        $this->order = new OrderBaseClient($this->api);
    }
}
