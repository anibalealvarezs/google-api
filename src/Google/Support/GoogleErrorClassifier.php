<?php

declare(strict_types=1);

namespace Anibalealvarezs\GoogleApi\Google\Support;

use Exception;
use GuzzleHttp\Exception\RequestException;

final class GoogleErrorClassifier
{
    /**
     * @param mixed $input
     * @return array<string, mixed>
     */
    public static function normalize(mixed $input): array
    {
        $payload = self::extractPayload($input);
        $error = is_array($payload['error'] ?? null) ? $payload['error'] : [];
        $errors = is_array($error['errors'] ?? null) ? $error['errors'] : [];

        $reason = null;
        if ($errors !== []) {
            $firstError = is_array($errors[0] ?? null) ? $errors[0] : [];
            $reason = self::normalizeString($firstError['reason'] ?? null);
        }

        return [
            'message' => self::normalizeString($error['message'] ?? null) ?? self::extractMessageFallback($input),
            'code' => self::normalizeInt($error['code'] ?? null),
            'status' => self::normalizeString($error['status'] ?? null),
            'reason' => $reason,
            'errors' => $errors,
            'raw' => $error,
            'root_error' => self::normalizeString($payload['error'] ?? null),
        ];
    }

    /**
     * @param mixed $input
     * @return array<string, mixed>
     */
    public static function classify(mixed $input): array
    {
        $normalized = self::normalize($input);
        $message = strtolower((string)($normalized['message'] ?? ''));
        $status = strtolower((string)($normalized['status'] ?? ''));
        $reason = strtolower((string)($normalized['reason'] ?? ''));
        $code = $normalized['code'];

        if (
            in_array($reason, ['quotaexceeded', 'dailylimitexceeded', 'userratelimitexceeded', 'ratelimitexceeded'], true)
            || str_contains($message, 'quota exceeded')
            || str_contains($message, 'rate limit exceeded')
            || str_contains($message, 'too many requests')
            || $status === 'resource_exhausted'
            || in_array($code, [429], true)
        ) {
            return [
                'category' => 'quota',
                'reason' => 'google_quota',
                'should_retry' => true,
                'delay_ms' => 1000,
            ];
        }

        if (
            in_array($reason, ['backenderror', 'internalerror'], true)
            || str_contains($message, 'backend error')
            || str_contains($message, 'temporarily unavailable')
            || in_array($status, ['internal', 'unavailable'], true)
            || in_array($code, [500, 503], true)
        ) {
            return [
                'category' => 'retryable',
                'reason' => 'google_transient',
                'should_retry' => true,
                'delay_ms' => 1000,
            ];
        }

        if (
            $normalized['root_error'] === 'invalid_grant'
            || str_contains($message, 'token has been expired or revoked')
        ) {
            return [
                'category' => 'permanent_auth',
                'reason' => 'google_auth_revoked',
                'should_retry' => false,
                'delay_ms' => 0,
            ];
        }

        return [
            'category' => 'unknown',
            'reason' => 'google_unknown',
            'should_retry' => false,
            'delay_ms' => 0,
        ];
    }

    public static function isRetryable(mixed $input): bool
    {
        return self::classify($input)['should_retry'] === true;
    }

    public static function isQuotaExceeded(mixed $input): bool
    {
        return self::classify($input)['category'] === 'quota';
    }

    public static function isPermanentAuthError(mixed $input): bool
    {
        return self::classify($input)['category'] === 'permanent_auth';
    }

    /**
     * @param mixed $input
     * @return array<string, mixed>
     */
    private static function extractPayload(mixed $input): array
    {
        if (is_array($input)) {
            return $input;
        }

        if ($input instanceof RequestException && $input->hasResponse()) {
            return self::extractPayloadFromResponseException($input);
        }

        if ($input instanceof Exception) {
            $prev = $input->getPrevious();
            if ($prev instanceof RequestException && $prev->hasResponse()) {
                return self::extractPayloadFromResponseException($prev);
            }

            $asJson = json_decode($input->getMessage(), true);
            return is_array($asJson) ? $asJson : [];
        }

        if (is_string($input)) {
            $asJson = json_decode($input, true);
            return is_array($asJson) ? $asJson : [];
        }

        return [];
    }

    /**
     * @return array<string, mixed>
     */
    private static function extractPayloadFromResponseException(RequestException $exception): array
    {
        $body = $exception->getResponse()->getBody();
        $body->rewind();
        $contents = json_decode($body->getContents(), true);
        $body->rewind();

        return is_array($contents) ? $contents : [];
    }

    private static function extractMessageFallback(mixed $input): ?string
    {
        if ($input instanceof Exception) {
            return $input->getMessage();
        }

        return self::normalizeString($input);
    }

    private static function normalizeString(mixed $value): ?string
    {
        if (!is_string($value) && !is_numeric($value)) {
            return null;
        }

        $normalized = trim((string)$value);
        return $normalized === '' ? null : $normalized;
    }

    private static function normalizeInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_int($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (int)$value;
        }

        return null;
    }
}

