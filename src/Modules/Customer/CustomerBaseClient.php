<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Customer;

use YieldTech\SdkPhp\Api\ApiClient;
use YieldTech\SdkPhp\Api\ApiResult;
use YieldTech\SdkPhp\Modules\Customer\Payloads\CustomerListPayload;
use YieldTech\SdkPhp\Modules\Customer\Payloads\CustomerRow;
use YieldTech\SdkPhp\Types\Page;

/**
 * @phpstan-import-type CustomerListParams from CustomerListPayload
 */
class CustomerBaseClient
{
    public function __construct(
        private readonly ApiClient $api,
    ) {
    }

    /**
     * @param ?CustomerListParams $params
     *
     * @return ApiResult<Page<CustomerRow>>
     */
    public function list(?array $params = null): ApiResult
    {
        $payload = $params === null ? null : CustomerListPayload::build($params);
        $response = $this->api->runQuery('/customer/list', $payload);

        return ApiClient::processResponse(
            $response,
            Page::buildWith([CustomerRow::class, 'fromPayload']),
        );
    }
}
