<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Self\Payloads;

use YieldTech\SdkPhp\Utils\AssertUtils;

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
            id: AssertUtils::assertString($payload['id'] ?? null),
            name: AssertUtils::assertString($payload['name'] ?? null),
            organization: SelfOrganizationInfo::fromPayload(AssertUtils::assertAssociativeArray($payload['organization'] ?? null)),
        );
    }
}
