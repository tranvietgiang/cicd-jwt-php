<?php

declare(strict_types=1);

namespace Core\Security;

use Core\Exceptions\HttpException;

final class Jwt
{
    public static function encode(array $payload): string
    {
        $now = time();
        $payload = array_merge([
            'iss' => config('jwt.issuer'),
            'iat' => $now,
            'exp' => $now + (int) config('jwt.ttl', 3600),
        ], $payload);

        $header = ['typ' => 'JWT', 'alg' => 'HS256'];
        $segments = [
            self::base64UrlEncode(json_encode($header, JSON_THROW_ON_ERROR)),
            self::base64UrlEncode(json_encode($payload, JSON_THROW_ON_ERROR)),
        ];
        $signature = hash_hmac('sha256', implode('.', $segments), config('jwt.secret'), true);
        $segments[] = self::base64UrlEncode($signature);

        return implode('.', $segments);
    }

    public static function decode(string $token): array
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            throw new HttpException('Invalid token', 401);
        }

        [$encodedHeader, $encodedPayload, $encodedSignature] = $parts;
        $expectedSignature = self::base64UrlEncode(
            hash_hmac('sha256', "{$encodedHeader}.{$encodedPayload}", config('jwt.secret'), true)
        );

        if (!hash_equals($expectedSignature, $encodedSignature)) {
            throw new HttpException('Invalid token signature', 401);
        }

        $payload = json_decode(self::base64UrlDecode($encodedPayload), true);

        if (!is_array($payload)) {
            throw new HttpException('Invalid token payload', 401);
        }

        if (($payload['exp'] ?? 0) < time()) {
            throw new HttpException('Token expired', 401);
        }

        return $payload;
    }

    private static function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private static function base64UrlDecode(string $value): string
    {
        $padding = 4 - (strlen($value) % 4);

        if ($padding < 4) {
            $value .= str_repeat('=', $padding);
        }

        return base64_decode(strtr($value, '-_', '+/')) ?: '';
    }
}
