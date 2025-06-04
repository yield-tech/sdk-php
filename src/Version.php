<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp;

final class Version
{
    public const NUMBER = '0.7.0';

    public static function getClientVersion(): string
    {
        $sdkVersion = self::NUMBER;

        preg_match('/^\d+(\.\d+)?/', \PHP_VERSION, $m);
        $phpMajorVersion = $m[0] ?? null;
        $runtimeVersion = $phpMajorVersion === null ? 'unknown' : "php {$phpMajorVersion}";

        return "Yield-SDK-PHP/{$sdkVersion} ({$runtimeVersion})";
    }
}
