<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Utils;

final class DateUtils
{
    public const DATE_FORMAT = 'Y-m-d';
    public const TIMESTAMP_FORMAT = 'Y-m-d\\TH:i:s.vp';

    public static function fromDatePayload(string $payload): \DateTimeImmutable
    {
        $date = \DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $payload, new \DateTimeZone('UTC'));
        if ($date === false) {
            throw new \Exception("Invalid date: {$payload}");
        }

        $date = $date->setTime(0, 0);

        return $date;
    }

    public static function buildDatePayload(\DateTimeInterface $date): string
    {
        return $date->format(self::DATE_FORMAT);
    }

    public static function fromTimestampPayload(string $payload): \DateTimeImmutable
    {
        $timestamp = \DateTimeImmutable::createFromFormat(self::TIMESTAMP_FORMAT, $payload);
        if ($timestamp === false) {
            throw new \Exception("Invalid timestamp: {$payload}");
        }

        $timestamp = $timestamp->setTimezone(new \DateTimeZone('UTC'));

        return $timestamp;
    }
}
