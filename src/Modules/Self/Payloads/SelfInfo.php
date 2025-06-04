<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Self\Payloads;

use YieldTech\SdkPhp\Utils\TypeUtils;

final readonly class SelfInfo
{
    private function __construct(
        public string $id,
        public string $name,
        public SelfOrganizationInfo $organization,
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromPayload(array $payload): self
    {
        return new self(
            id: TypeUtils::expectString($payload['id'] ?? null),
            name: TypeUtils::expectString($payload['name'] ?? null),
            organization: SelfOrganizationInfo::fromPayload(TypeUtils::expectRecord($payload['organization'] ?? null)),
        );
    }
}
