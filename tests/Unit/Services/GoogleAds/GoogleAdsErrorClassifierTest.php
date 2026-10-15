<?php

declare(strict_types=1);

namespace Tests\Unit\Services\GoogleAds;

use Anibalealvarezs\GoogleApi\Google\Support\GoogleErrorClassifier;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class GoogleAdsErrorClassifierTest extends TestCase
{
    public function testClassifiesResourceExhaustedAsQuota(): void
    {
        $payload = [
            'error' => [
                'code' => 429,
                'message' => 'Resource has been exhausted (e.g. check quota).',
                'status' => 'RESOURCE_EXHAUSTED',
                'details' => [
                    [
                        'errors' => [
                            [
                                'errorCode' => ['quotaError' => 'RESOURCE_EXHAUSTED'],
                                'message' => 'Too many requests for customer.',
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $classification = GoogleErrorClassifier::classify($payload);
        $this->assertEquals('quota', $classification['category']);
        $this->assertTrue(GoogleErrorClassifier::isRetryable($payload));
        $this->assertTrue(GoogleErrorClassifier::isQuotaExceeded($payload));
        $this->assertFalse(GoogleErrorClassifier::isPermanentAuthError($payload));
    }

    public function testClassifiesTransientConcurrencyErrorAsRetryable(): void
    {
        $payload = [
            'error' => [
                'code' => 500,
                'message' => 'Concurrent modification error occurred.',
                'status' => 'INTERNAL',
                'details' => [
                    [
                        'errors' => [
                            [
                                'errorCode' => ['databaseError' => 'CONCURRENT_MODIFICATION'],
                                'message' => 'Multiple operations on customer simultaneously.',
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $classification = GoogleErrorClassifier::classify($payload);
        $this->assertEquals('retryable', $classification['category']);
        $this->assertTrue(GoogleErrorClassifier::isRetryable($payload));
        $this->assertFalse(GoogleErrorClassifier::isQuotaExceeded($payload));
    }

    public function testClassifiesPermanentAuthErrors(): void
    {
        $payload = [
            'error' => [
                'code' => 401,
                'message' => 'User is NOT_ADS_USER or token revoked.',
                'status' => 'UNAUTHENTICATED',
                'details' => [
                    [
                        'errors' => [
                            [
                                'errorCode' => ['authenticationError' => 'NOT_ADS_USER'],
                                'message' => 'User in the query is not a Google Ads account holder.',
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $classification = GoogleErrorClassifier::classify($payload);
        $this->assertEquals('permanent_auth', $classification['category']);
        $this->assertTrue(GoogleErrorClassifier::isPermanentAuthError($payload));
        $this->assertFalse(GoogleErrorClassifier::isRetryable($payload));
    }

    public function testClassifiesDeveloperTokenProhibitedAsPermanentAuth(): void
    {
        $payload = [
            'error' => [
                'code' => 403,
                'message' => 'The developer token is not approved or prohibited.',
                'details' => [
                    [
                        'errors' => [
                            [
                                'errorCode' => ['authorizationError' => 'DEVELOPER_TOKEN_PROHIBITED'],
                                'message' => 'Developer token is not allowed to access this customer.',
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->assertTrue(GoogleErrorClassifier::isPermanentAuthError($payload));
    }
}
