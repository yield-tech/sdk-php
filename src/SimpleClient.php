<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use YieldTech\SdkPhp\Api\ApiClient;
use YieldTech\SdkPhp\Modules\Customer\SimpleCustomerClient;
use YieldTech\SdkPhp\Modules\Order\SimpleOrderClient;
use YieldTech\SdkPhp\Modules\Self\SimpleSelfClient;

class SimpleClient
{
    public readonly SimpleSelfClient $self;
    public readonly SimpleCustomerClient $customer;
    public readonly SimpleOrderClient $order;

    /**
     * @param array{
     *   base_url?: string,
     *   force_sandbox?: bool,
     *   http_client?: ClientInterface,
     *   http_factory?: RequestFactoryInterface&StreamFactoryInterface,
     * } $options
     */
    public function __construct(string $apiKeyId, string $apiKeySecret, array $options = [])
    {
        $apiClient = new ApiClient($apiKeyId, $apiKeySecret, $options);

        $this->self = new SimpleSelfClient($apiClient);
        $this->customer = new SimpleCustomerClient($apiClient);
        $this->order = new SimpleOrderClient($apiClient);
    }
}
