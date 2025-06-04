<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Self\Payloads;

use YieldTech\SdkPhp\Utils\TypeUtils;

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
            id: TypeUtils::expectString($payload['id'] ?? null),
            registeredName: TypeUtils::expectString($payload['registered_name'] ?? null),
            tradeName: isset($payload['trade_name']) ? TypeUtils::expectString($payload['trade_name']) : null,
        );
    }
}
