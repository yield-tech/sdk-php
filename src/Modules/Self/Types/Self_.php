<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Self\Types;

final class Self_
{
    private function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly SelfOrganization $organization,
    ) {
    }

    // @phpstan-ignore missingType.iterableValue
    public static function fromPayload(array $payload): self
    {
        return new self(
            id: $payload['id'],
            name: $payload['name'],
            organization: SelfOrganization::fromPayload($payload['organization']),
        );
    }
}
