<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Self;

use YieldTech\SdkPhp\Modules\Self\Payloads\SelfInfo;

class SelfClient
{
    public function __construct(
        private readonly SelfBaseClient $base,
    ) {
    }

    public function info(): SelfInfo
    {
        return $this->base->info()->getData();
    }
}
