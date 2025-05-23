<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Self\Payloads;

use YieldTech\SdkPhp\Utils\AssertUtils;

final readonly class SelfOrganizationInfo
{
    private function __construct(
        public string $id,
        public string $registeredName,
        public ?string $tradeName,
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromPayload(array $payload): self
    {
        return new self(
            id: AssertUtils::assertString($payload['id'] ?? null),
            registeredName: AssertUtils::assertString($payload['registered_name'] ?? null),
            tradeName: isset($payload['trade_name']) ? AssertUtils::assertString($payload['trade_name']) : null,
        );
    }
}
