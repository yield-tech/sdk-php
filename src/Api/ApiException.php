<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Api;

class ApiException extends \Exception
{
    public function __construct(
        private readonly int $statusCode,
        private readonly ?string $requestId,
        private readonly ApiErrorDetails $error,
    ) {
        $errorInfo = $error->getType();

        if ($error->getType() === 'validation_error' && $error->getBody() !== null) {
            $issues = json_encode($error->getBody()['issues'], \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE);
            $errorInfo = "{$errorInfo} {$issues}";
        }

        if ($error->getException() !== null) {
            $message = json_encode($error->getException()->getMessage(), \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE);
            $errorInfo = "{$errorInfo} {$message}";
        }

        $idValue = $requestId ?? '<none>';
        $extraInfo = implode('; ', [
            "status_code={$statusCode}",
            "request_id={$idValue}",
        ]);

        parent::__construct("Yield API error: {$errorInfo} [{$extraInfo}]", 0, $error->getException());
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    public function getDetails(): ApiErrorDetails
    {
        return $this->error;
    }
}
