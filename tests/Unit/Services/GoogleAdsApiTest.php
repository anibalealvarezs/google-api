<?php

namespace Tests\Unit\Services;

use Anibalealvarezs\GoogleApi\Services\GoogleAds\GoogleAdsApi;
use Exception;
use Faker\Factory as Faker;
use Faker\Generator;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Anibalealvarezs\ApiSkeleton\Classes\Exceptions\ApiRequestException;

class GoogleAdsApiTest extends TestCase
{
    protected Generator $faker;
    protected string $redirectUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $refreshToken;
    protected string $userId;
    protected array $scopes;
    protected string $token;
    protected string $developerToken;
    protected string $customerId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
        $this->redirectUrl = 'https://example.com/callback';
        $this->clientId = $this->faker->uuid;
        $this->clientSecret = $this->faker->uuid;
        $this->refreshToken = $this->faker->uuid;
        $this->userId = $this->faker->userName;
        $this->scopes = ['https://www.googleapis.com/auth/adwords'];
        $this->token = $this->faker->uuid;
        $this->developerToken = 'dev-token-123';
        $this->customerId = '1234567890';
    }

    protected function createMockedGuzzleClient(?array $responses = null, ?MockHandler $mock = null): GuzzleClient
    {
        if ($mock === null) {
            $mock = new MockHandler($responses);
        }
        $handler = HandlerStack::create($mock);
        return new GuzzleClient(['handler' => $handler]);
    }

    /**
     * @throws Exception
     */
    public function testConstructorWithValidParameters(): void
    {
        $client = new GoogleAdsApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            developerToken: $this->developerToken,
            scopes: $this->scopes,
            token: $this->token
        );

        $this->assertEquals('https://googleads.googleapis.com/v17/', $client->getBaseUrl());
        $this->assertEquals($this->scopes, $client->getScopes());
        $headers = $client->getHeaders();
        $this->assertArrayHasKey('developer-token', $headers);
        $this->assertEquals($this->developerToken, $headers['developer-token']);
    }

    /**
     * @throws Exception
     */
    public function testConstructorWithLoginCustomerId(): void
    {
        $loginCustomerId = '9999999999';
        $client = new GoogleAdsApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            developerToken: $this->developerToken,
            loginCustomerId: $loginCustomerId,
            scopes: $this->scopes,
            token: $this->token
        );

        $headers = $client->getHeaders();
        $this->assertArrayHasKey('login-customer-id', $headers);
        $this->assertEquals($loginCustomerId, $headers['login-customer-id']);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testSearchSuccess(): void
    {
        $responseData = [
            'results' => [
                [
                    'campaign' => [
                        'resourceName' => 'customers/123/campaigns/456',
                        'id' => '456',
                    ],
                    'metrics' => [
                        'impressions' => '1000',
                    ]
                ]
            ]
        ];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new GoogleAdsApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            developerToken: $this->developerToken,
            guzzleClient: $guzzle
        );

        $query = "SELECT campaign.id, metrics.impressions FROM campaign WHERE segments.date DURING LAST_7_DAYS";
        $result = $client->search($this->customerId, $query);

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals("https://googleads.googleapis.com/v17/customers/{$this->customerId}/googleAds:search", (string)$lastRequest->getUri());
        
        $requestBody = json_decode($lastRequest->getBody()->getContents(), true);
        $this->assertEquals($query, $requestBody['query']);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testSearchAllSuccess(): void
    {
        $response1 = [
            'results' => [['campaign' => ['id' => '1']]],
            'nextPageToken' => 'token1'
        ];
        $response2 = [
            'results' => [['campaign' => ['id' => '2']]]
        ];

        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => 'abc'])),
            new Response(200, [], json_encode($response1)),
            new Response(200, [], json_encode($response2))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new GoogleAdsApi($this->redirectUrl, $this->clientId, $this->clientSecret, $this->refreshToken, $this->userId, $this->developerToken, guzzleClient: $guzzle);
        $result = $client->searchAll($this->customerId, "SELECT ...");

        $this->assertCount(2, $result['results']);
        $this->assertEquals('1', $result['results'][0]['campaign']['id']);
        $this->assertEquals('2', $result['results'][1]['campaign']['id']);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testSearchAllAndProcessSuccess(): void
    {
        $response1 = ['results' => [['id' => 1]], 'nextPageToken' => 't1'];
        $response2 = ['results' => [['id' => 2]]];

        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => 'abc'])),
            new Response(200, [], json_encode($response1)),
            new Response(200, [], json_encode($response2))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new GoogleAdsApi($this->redirectUrl, $this->clientId, $this->clientSecret, $this->refreshToken, $this->userId, $this->developerToken, guzzleClient: $guzzle);
        
        $called = 0;
        $client->searchAllAndProcess($this->customerId, "SELECT ...", function($results) use (&$called) {
            $called++;
            $this->assertCount(1, $results);
        });

        $this->assertEquals(2, $called);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testSearchAllAndProcessErrorMidLoop(): void
    {
        $response1 = ['results' => [['id' => 1]], 'nextPageToken' => 't1'];

        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => 'abc'])),
            new Response(200, [], json_encode($response1)),
            new Response(500, [], 'Internal Server Error'),
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new GoogleAdsApi($this->redirectUrl, $this->clientId, $this->clientSecret, $this->refreshToken, $this->userId, $this->developerToken, guzzleClient: $guzzle);

        $this->expectException(ApiRequestException::class);

        $client->searchAllAndProcess($this->customerId, "SELECT ...", function($results) {});
    }
}
