<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Utils;

class TypeUtils
{
    public const DATE_FORMAT = 'Y-m-d';
    public const TIME_FORMAT = 'Y-m-d\\TH:i:s.vp';

    public static function expectBoolean(mixed $data): bool
    {
        if (!\is_bool($data)) {
            throw new \DomainException(\sprintf('Expected bool, got %s', \gettype($data)));
        }

        return $data;
    }

    public static function expectInteger(mixed $data): int
    {
        if (!\is_int($data)) {
            throw new \DomainException(\sprintf('Expected int, got %s', \gettype($data)));
        }

        return $data;
    }

    public static function expectString(mixed $data): string
    {
        if (!\is_string($data)) {
            throw new \DomainException(\sprintf('Expected string, got %s', \gettype($data)));
        }

        return $data;
    }

    public static function expectDate(mixed $data): \DateTimeImmutable
    {
        if (!\is_string($data)) {
            throw new \DomainException(\sprintf('Expected string, got %s', \gettype($data)));
        }

        $date = \DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $data, new \DateTimeZone('UTC'));
        if ($date === false) {
            throw new \InvalidArgumentException("Invalid date: \"{$data}\"");
        }

        $date = $date->setTime(0, 0);

        return $date;
    }

    public static function expectTime(mixed $data): \DateTimeImmutable
    {
        if (!\is_string($data)) {
            throw new \DomainException(\sprintf('Expected string, got %s', \gettype($data)));
        }

        $time = \DateTimeImmutable::createFromFormat(self::TIME_FORMAT, $data);
        if ($time === false) {
            throw new \InvalidArgumentException("Invalid time: \"{$data}\"");
        }

        $time = $time->setTimezone(new \DateTimeZone('UTC'));

        return $time;
    }

    /**
     * @return array<string, mixed>
     */
    public static function expectRecord(mixed $data): array
    {
        if (!\is_array($data)) {
            throw new \DomainException(\sprintf('Expected array, got %s', \gettype($data)));
        }

        if (array_is_list($data) && \count($data) > 0) {
            throw new \DomainException('Expected associative array, got indexed array');
        }

        // @phpstan-ignore return.type
        return $data;
    }

    /**
     * @return array<mixed>
     */
    public static function expectList(mixed $data): array
    {
        if (!\is_array($data)) {
            throw new \DomainException(\sprintf('Expected array, got %s', \gettype($data)));
        }

        if (!array_is_list($data)) {
            throw new \DomainException('Expected indexed array, got associative array');
        }

        return $data;
    }

    /**
     * @return array<array<string, mixed>>
     */
    public static function expectRecordList(mixed $data): array
    {
        return array_map([self::class, 'expectRecord'], self::expectList($data));
    }
}
