<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Self;

use YieldTech\SdkPhp\Api\ApiClient;
use YieldTech\SdkPhp\Api\ApiResult;
use YieldTech\SdkPhp\Modules\Self\Types\Self_;

class SelfClient
{
    public function __construct(
        private readonly ApiClient $api,
    ) {
    }

    /** @return ApiResult<Self_> */
    public function info(): ApiResult
    {
        return $this->api->runQuery([Self_::class, 'fromPayload'], '/self/info');
    }
}
