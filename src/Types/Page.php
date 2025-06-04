<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Types;

use YieldTech\SdkPhp\Utils\TypeUtils;

/**
 * @template T
 *
 * @implements \ArrayAccess<int, T>
 * @implements \IteratorAggregate<int, T>
 */
class Page implements \ArrayAccess, \IteratorAggregate, \Countable
{
    /**
     * @param array<T> $items
     */
    public function __construct(
        private readonly array $items,
        private readonly bool $hasMore,
    ) {
    }

    /**
     * @template E
     *
     * @param callable(array<string, mixed>): E $fromPayload
     *
     * @return callable(array<string, mixed> $payload): Page<E>
     */
    public static function buildWith(callable $fromPayload): callable
    {
        return fn (array $payload) => new Page(
            items: array_map($fromPayload, TypeUtils::expectRecordList($payload['items'] ?? null)),
            hasMore: TypeUtils::expectBoolean($payload['has_more'] ?? null),
        );
    }

    public function hasMore(): bool
    {
        return $this->hasMore;
    }

    /**
     * @return array<T>
     */
    public function toArray(): array
    {
        return $this->items;
    }

    // ==== ArrayAccess ==== //

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * @return T
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new \BadMethodCallException('Mutation is not allowed');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new \BadMethodCallException('Mutation is not allowed');
    }

    // ==== IteratorAggregate ==== //

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }

    // ==== Countable ==== //

    public function count(): int
    {
        return \count($this->items);
    }
}
