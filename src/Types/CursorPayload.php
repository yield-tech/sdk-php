<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Types;

/**
 * @phpstan-type CursorLike string|object{id: string}|Page<object{id: string}>
 */
class CursorPayload
{
    /**
     * @param CursorLike $cursor
     */
    public static function build(mixed $cursor): ?string
    {
        if (\is_string($cursor)) {
            return $cursor;
        } elseif ($cursor instanceof Page) {
            /** @var object{id: string}|null */
            $last = $cursor[\count($cursor) - 1] ?? null;

            return $last?->id;
        } else {
            return $cursor->id;
        }
    }
}
