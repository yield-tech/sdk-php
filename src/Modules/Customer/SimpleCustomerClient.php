<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Customer;

use YieldTech\SdkPhp\Api\ApiClient;
use YieldTech\SdkPhp\Modules\Customer\Types\Customer;

class SimpleCustomerClient
{
    private readonly CustomerClient $innerClient;

    public function __construct(
        ApiClient $api,
    ) {
        $this->innerClient = new CustomerClient($api);
    }

    public function fetch(string $id): Customer
    {
        return $this->innerClient->fetch($id)->getData();
    }
}
