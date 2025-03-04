<?php

declare(strict_types=1);

namespace YieldTech\SdkPhp\Utils;

final class Base64UrlUtils
{
    public static function encode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function decode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
