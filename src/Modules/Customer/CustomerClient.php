<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Customer;

use YieldTech\SdkPhp\Modules\Customer\Payloads\CustomerListPayload;
use YieldTech\SdkPhp\Modules\Customer\Payloads\CustomerRow;
use YieldTech\SdkPhp\Types\Page;

/**
 * @phpstan-import-type CustomerListParams from CustomerListPayload
 */
class CustomerClient
{
    public function __construct(
        private readonly CustomerBaseClient $base,
    ) {
    }

    /**
     * @param ?CustomerListParams $params
     *
     * @return Page<CustomerRow>
     */
    public function list(?array $params = null): Page
    {
        return $this->base->list($params)->getData();
    }
}
