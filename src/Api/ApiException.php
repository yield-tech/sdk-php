<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Api;

class ApiException extends \Exception
{
    /** @var ApiResult<*> */
    private readonly ApiResult $result;

    /**
     * @param ApiResult<*> $result
     */
    public function __construct(ApiResult $result)
    {
        $error = $result->getError();
        if ($error === null) {
            throw new \InvalidArgumentException('Expected ApiResult failure, got success');
        }

        $errorType = $error->getType();
        $statusCode = $result->getStatusCode();

        parent::__construct("Yield API error: {$errorType} [status_code={$statusCode}]");

        $this->result = $result;
    }

    /**
     * @return ApiResult<*>
     */
    public function getResult(): ApiResult
    {
        return $this->result;
    }
}
