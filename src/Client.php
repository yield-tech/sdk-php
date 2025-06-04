<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp;

use YieldTech\SdkPhp\Api\ApiClient;
use YieldTech\SdkPhp\Modules\Customer\CustomerClient;
use YieldTech\SdkPhp\Modules\Order\OrderClient;
use YieldTech\SdkPhp\Modules\Self\SelfClient;

/**
 * @phpstan-import-type ClientOptions from ApiClient
 */
class Client
{
    public readonly BaseClient $base;

    public readonly CustomerClient $customer;
    public readonly OrderClient $order;
    public readonly SelfClient $self;

    /**
     * @param ClientOptions $options
     */
    public function __construct(string $apiKey, array $options = [])
    {
        $this->base = new BaseClient($apiKey, $options);

        $this->customer = new CustomerClient($this->base->customer);
        $this->order = new OrderClient($this->base->order);
        $this->self = new SelfClient($this->base->self);
    }
}
