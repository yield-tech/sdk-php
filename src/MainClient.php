<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use YieldTech\SdkPhp\Api\ApiClient;
use YieldTech\SdkPhp\Modules\Customer\CustomerClient;
use YieldTech\SdkPhp\Modules\Order\OrderClient;
use YieldTech\SdkPhp\Modules\Self\SelfClient;

class MainClient
{
    public readonly SelfClient $self;
    public readonly CustomerClient $customer;
    public readonly OrderClient $order;

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

        $this->self = new SelfClient($apiClient);
        $this->customer = new CustomerClient($apiClient);
        $this->order = new OrderClient($apiClient);
    }
}
