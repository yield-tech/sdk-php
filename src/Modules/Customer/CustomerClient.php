<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Customer;

use YieldTech\SdkPhp\Api\ApiClient;
use YieldTech\SdkPhp\Api\ApiResult;
use YieldTech\SdkPhp\Modules\Customer\Types\Customer;

class CustomerClient
{
    public function __construct(
        private readonly ApiClient $api,
    ) {
    }

    /** @return ApiResult<Customer> */
    public function fetch(string $id): ApiResult
    {
        return $this->api->runQuery([Customer::class, 'fromPayload'], "/customer/fetch/{$id}");
    }
}
