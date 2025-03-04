<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Api;

class ApiException extends \Exception
{
    private readonly ApiErrorDetails $details;

    public function __construct(ApiErrorDetails $details)
    {
        // TODO: Provide a more descriptive error message,
        // including the status code and previous exception (if any)
        parent::__construct('Yield API error');

        $this->details = $details;
    }

    public function getDetails(): ApiErrorDetails
    {
        return $this->details;
    }
}
