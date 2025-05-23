<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Utils;

class AssertUtils
{
    public const DATE_FORMAT = 'Y-m-d';
    public const TIME_FORMAT = 'Y-m-d\\TH:i:s.vp';

    public static function assertBoolean(mixed $data): bool
    {
        if (!\is_bool($data)) {
            throw new \InvalidArgumentException(\sprintf('Expected boolean, got %s', \gettype($data)));
        }

        return $data;
    }

    public static function assertInteger(mixed $data): int
    {
        if (!\is_int($data)) {
            throw new \InvalidArgumentException(\sprintf('Expected integer, got %s', \gettype($data)));
        }

        return $data;
    }

    public static function assertString(mixed $data): string
    {
        if (!\is_string($data)) {
            throw new \InvalidArgumentException(\sprintf('Expected string, got %s', \gettype($data)));
        }

        return $data;
    }

    public static function assertDate(mixed $data): \DateTimeImmutable
    {
        if (!\is_string($data)) {
            throw new \InvalidArgumentException(\sprintf('Expected string, got %s', \gettype($data)));
        }

        $date = \DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $data, new \DateTimeZone('UTC'));
        if ($date === false) {
            throw new \InvalidArgumentException("Invalid date: {$data}");
        }

        $date = $date->setTime(0, 0);

        return $date;
    }

    public static function assertTime(mixed $data): \DateTimeImmutable
    {
        if (!\is_string($data)) {
            throw new \InvalidArgumentException(\sprintf('Expected string, got %s', \gettype($data)));
        }

        $time = \DateTimeImmutable::createFromFormat(self::TIME_FORMAT, $data);
        if ($time === false) {
            throw new \InvalidArgumentException("Invalid timestamp: {$data}");
        }

        $time = $time->setTimezone(new \DateTimeZone('UTC'));

        return $time;
    }

    /**
     * @return array<string, mixed>
     */
    public static function assertAssociativeArray(mixed $data): array
    {
        if (!\is_array($data) || array_is_list($data) && \count($data) > 0) {
            throw new \InvalidArgumentException(\sprintf('Expected associative array, got %s', \gettype($data)));
        }

        // @phpstan-ignore return.type
        return $data;
    }
}
