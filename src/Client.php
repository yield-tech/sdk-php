<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp;

use YieldTech\SdkPhp\Api\ApiClient;
use YieldTech\SdkPhp\Modules\Order\OrderClient;
use YieldTech\SdkPhp\Modules\Self\SelfClient;

/**
 * @phpstan-import-type ClientOptions from ApiClient
 */
class Client
{
    public readonly BaseClient $base;

    public readonly SelfClient $self;
    public readonly OrderClient $order;

    /**
     * @param ClientOptions $options
     */
    public function __construct(string $apiKey, array $options = [])
    {
        $this->base = new BaseClient($apiKey, $options);

        $this->self = new SelfClient($this->base->self);
        $this->order = new OrderClient($this->base->order);
    }
}
