<?php

namespace Tests\Unit;

use Anibalealvarezs\ApiSkeleton\Classes\Exceptions\ApiRequestException;
use Anibalealvarezs\GoogleApi\Google\GoogleApi;
use Faker\Factory as Faker;
use Faker\Generator;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

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
}
