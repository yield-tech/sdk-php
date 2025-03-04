<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Modules\Self\Types;

final class SelfOrganization
{
    private function __construct(
        public readonly string $id,
        public readonly string $registeredName,
        public readonly ?string $tradeName,
    ) {
    }

    // @phpstan-ignore missingType.iterableValue
    public static function fromPayload(array $payload): self
    {
        return new self(
            id: $payload['id'],
            registeredName: $payload['registered_name'],
            tradeName: $payload['trade_name'],
        );
    }
}
