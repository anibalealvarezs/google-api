<?php

namespace Tests\Unit\Services;

use Anibalealvarezs\GoogleApi\Services\YouTubeAnalytics\YouTubeAnalyticsApi;
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

class YouTubeAnalyticsApiTest extends TestCase
{
    protected Generator $faker;
    protected string $redirectUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $refreshToken;
    protected string $userId;
    protected array $scopes;
    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
        $this->redirectUrl = 'https://example.com/callback';
        $this->clientId = $this->faker->uuid;
        $this->clientSecret = $this->faker->uuid;
        $this->refreshToken = $this->faker->uuid;
        $this->userId = $this->faker->userName;
        $this->scopes = ['https://www.googleapis.com/auth/yt-analytics.readonly'];
        $this->token = $this->faker->uuid;
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
        $client = new YouTubeAnalyticsApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            scopes: $this->scopes,
            token: $this->token
        );

        $this->assertEquals('https://youtubeanalytics.googleapis.com/v2/', $client->getBaseUrl());
        $this->assertEquals($this->scopes, $client->getScopes());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testQuerySuccess(): void
    {
        $responseData = [
            'kind' => 'youtubeAnalytics#resultTable',
            'columnHeaders' => [
                ['name' => 'day', 'columnType' => 'DIMENSION', 'dataType' => 'STRING'],
                ['name' => 'views', 'columnType' => 'METRIC', 'dataType' => 'INTEGER']
            ],
            'rows' => [
                ['2023-01-01', 100],
                ['2023-01-02', 150]
            ]
        ];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new YouTubeAnalyticsApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $params = [
            'ids' => 'channel==MINE',
            'startDate' => '2023-01-01',
            'endDate' => '2023-01-31',
            'metrics' => 'views',
            'dimensions' => 'day'
        ];

        $result = $client->query($params);

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('GET', $lastRequest->getMethod());
        $this->assertStringContainsString('reports', (string)$lastRequest->getUri());
        foreach ($params as $key => $value) {
            $this->assertStringContainsString(urlencode($key) . '=' . urlencode($value), (string)$lastRequest->getUri());
        }
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testQueryAllSuccess(): void
    {
        $response1 = [
            'rows' => array_fill(0, 5, ['2023-01-01', 100])
        ];
        $response2 = [
            'rows' => [['2023-01-06', 200]]
        ];

        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => 'abc'])),
            new Response(200, [], json_encode($response1)),
            new Response(200, [], json_encode($response2))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new YouTubeAnalyticsApi($this->redirectUrl, $this->clientId, $this->clientSecret, $this->refreshToken, $this->userId, guzzleClient: $guzzle);
        $result = $client->queryAll(['maxResults' => 5]);

        $this->assertCount(6, $result['rows']);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testQueryAllAndProcessSuccess(): void
    {
        $response1 = ['rows' => array_fill(0, 5, [])];
        $response2 = ['rows' => [[]]];

        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => 'abc'])),
            new Response(200, [], json_encode($response1)),
            new Response(200, [], json_encode($response2))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new YouTubeAnalyticsApi($this->redirectUrl, $this->clientId, $this->clientSecret, $this->refreshToken, $this->userId, guzzleClient: $guzzle);
        
        $called = 0;
        $client->queryAllAndProcess(['maxResults' => 5], function($rows) use (&$called) {
            $called++;
        });

        $this->assertEquals(2, $called);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testQueryAllAndProcessErrorMidLoop(): void
    {
        $response1 = ['rows' => array_fill(0, 5, [])];

        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => 'abc'])),
            new Response(200, [], json_encode($response1)),
            new Response(500, [], 'Internal Server Error'),
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new YouTubeAnalyticsApi($this->redirectUrl, $this->clientId, $this->clientSecret, $this->refreshToken, $this->userId, guzzleClient: $guzzle);

        $this->expectException(ApiRequestException::class);

        $client->queryAllAndProcess(['maxResults' => 5], function($rows) {});
    }
}
