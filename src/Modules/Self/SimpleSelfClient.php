<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Self;

use YieldTech\SdkPhp\Api\ApiClient;
use YieldTech\SdkPhp\Modules\Self\Types\Self_;

class SimpleSelfClient
{
    private readonly SelfClient $innerClient;

    public function __construct(
        ApiClient $api,
    ) {
        $this->innerClient = new SelfClient($api);
    }

    public function info(): Self_
    {
        return $this->innerClient->info()->getData();
    }
}
