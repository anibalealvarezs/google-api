<?php

namespace Tests\Unit;

use Anibalealvarezs\ApiSkeleton\Classes\Exceptions\ApiRequestException;
use Anibalealvarezs\GoogleApi\Google\GoogleApi;
use Anibalealvarezs\GoogleApi\Google\Exceptions\GoogleQuotaExceededException;
use Anibalealvarezs\GoogleApi\Google\Support\GoogleErrorClassifier;
use Faker\Factory as Faker;
use Faker\Generator;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Exception;

class GoogleErrorHandlingTest extends TestCase
{
    protected Generator $faker;
    protected string $baseUrl;
    protected string $redirectUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $refreshToken;
    protected string $userId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
        $this->baseUrl = 'https://www.googleapis.com';
        $this->redirectUrl = 'https://example.com/callback';
        $this->clientId = $this->faker->uuid;
        $this->clientSecret = $this->faker->uuid;
        $this->refreshToken = $this->faker->uuid;
        $this->userId = $this->faker->userName;
    }

    protected function createMockedGoogleClient(array $responses): GoogleApi
    {
        $mock = new MockHandler($responses);
        $handler = HandlerStack::create($mock);
        $guzzle = new GuzzleClient(['handler' => $handler]);

        return new GoogleApi(
            baseUrl: $this->baseUrl,
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            token: 'dummy-token',
            guzzleClient: $guzzle
        );
    }

    public function testGoogleFalsy200ErrorDetection(): void
    {
        $errorBody = [
            'error' => [
                'code' => 400,
                'message' => 'Specific Google Error Message',
                'status' => 'INVALID_ARGUMENT'
            ]
        ];

        $client = $this->createMockedGoogleClient([
            new Response(200, [], json_encode($errorBody))
        ]);

        $this->expectException(ApiRequestException::class);
        $this->expectExceptionMessage('Specific Google Error Message');

        $client->performRequest('GET', '/test');
    }

    public function testGoogleSuccessResponseRemainsIntact(): void
    {
        $successBody = [
            'kind' => 'drive#fileList',
            'files' => []
        ];

        $client = $this->createMockedGoogleClient([
            new Response(200, [], json_encode($successBody))
        ]);

        $response = $client->performRequest('GET', '/test');
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getBody()->getContents(), true);
        $this->assertEquals('drive#fileList', $data['kind']);
    }

    public function testGoogleSemanticRetryableFalsy200EventuallySucceeds(): void
    {
        $retryableBody = [
            'error' => [
                'code' => 429,
                'message' => 'Quota policy temporarily exceeded.',
                'status' => 'RESOURCE_EXHAUSTED',
                'errors' => [
                    ['reason' => 'userRateLimitExceeded'],
                ],
            ],
        ];
        $successBody = [
            'kind' => 'drive#fileList',
            'files' => [],
        ];

        $client = $this->createMockedGoogleClient([
            new Response(200, [], json_encode($retryableBody)),
            new Response(200, [], json_encode($successBody)),
        ]);

        $response = $client->performRequest('GET', '/test');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(is_callable($client->getRateLimitDetector()));
    }

    public function testGoogleQuotaExceptionUsesStructuredReason(): void
    {
        $this->expectException(GoogleQuotaExceededException::class);

        $client = new class(
            baseUrl: $this->baseUrl,
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            token: 'dummy-token'
        ) extends GoogleApi {
            public function exposeHandleException(Exception $exception): mixed
            {
                return $this->handleException($exception);
            }
        };

        $client->exposeHandleException(new Exception(json_encode([
            'error' => [
                'code' => 429,
                'message' => 'policy block',
                'status' => 'RESOURCE_EXHAUSTED',
                'errors' => [
                    ['reason' => 'quotaExceeded'],
                ],
            ],
        ])));
    }

    public function testGoogleErrorClassifierRecognizesBackendErrorAsRetryable(): void
    {
        $payload = [
            'error' => [
                'code' => 503,
                'message' => 'Backend Error',
                'status' => 'UNAVAILABLE',
                'errors' => [
                    ['reason' => 'backendError'],
                ],
            ],
        ];

        $classification = GoogleErrorClassifier::classify($payload);
        $this->assertSame('retryable', $classification['category']);
        $this->assertTrue($classification['should_retry']);
    }
}
