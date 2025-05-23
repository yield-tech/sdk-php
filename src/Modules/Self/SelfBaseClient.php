<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Self;

use YieldTech\SdkPhp\Api\ApiClient;
use YieldTech\SdkPhp\Api\ApiResult;
use YieldTech\SdkPhp\Modules\Self\Payloads\SelfInfo;

class SelfBaseClient
{
    public function __construct(
        private readonly ApiClient $api,
    ) {
    }

    /** @return ApiResult<SelfInfo> */
    public function info(): ApiResult
    {
        $response = $this->api->runQuery('/self/info');

        return ApiClient::processResponse($response, [SelfInfo::class, 'fromPayload']);
    }
}
