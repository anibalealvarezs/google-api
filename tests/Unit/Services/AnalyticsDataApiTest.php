<?php

namespace Tests\Unit\Services;

use Anibalealvarezs\GoogleApi\Services\AnalyticsData\AnalyticsDataApi;
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

class AnalyticsDataApiTest extends TestCase
{
    protected Generator $faker;
    protected string $redirectUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $refreshToken;
    protected string $userId;
    protected array $scopes;
    protected string $token;
    protected string $propertyId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
        $this->redirectUrl = 'https://example.com/callback';
        $this->clientId = $this->faker->uuid;
        $this->clientSecret = $this->faker->uuid;
        $this->refreshToken = $this->faker->uuid;
        $this->userId = $this->faker->userName;
        $this->scopes = ['https://www.googleapis.com/auth/analytics.readonly'];
        $this->token = $this->faker->uuid;
        $this->propertyId = '123456789';
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
        $client = new AnalyticsDataApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            scopes: $this->scopes,
            token: $this->token
        );

        $this->assertEquals('https://analyticsdata.googleapis.com/v1beta/', $client->getBaseUrl());
        $this->assertEquals($this->scopes, $client->getScopes());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testRunReportSuccess(): void
    {
        $responseData = [
            'rows' => [
                [
                    'dimensionValues' => [['value' => 'test_city']],
                    'metricValues' => [['value' => '10']]
                ]
            ]
        ];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new AnalyticsDataApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $payload = [
            'dateRanges' => [['startDate' => '2023-01-01', 'endDate' => '2023-01-31']],
            'dimensions' => [['name' => 'city']],
            'metrics' => [['name' => 'activeUsers']],
        ];

        $result = $client->runReport($this->propertyId, $payload);

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals("https://analyticsdata.googleapis.com/v1beta/properties/{$this->propertyId}:runReport", (string)$lastRequest->getUri());
        $this->assertEquals(json_encode($payload), $lastRequest->getBody()->getContents());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testRunSimpleReportSuccess(): void
    {
        $responseData = ['rows' => []];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new AnalyticsDataApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $metrics = ['activeUsers'];
        $dimensions = ['city'];
        $result = $client->runSimpleReport($this->propertyId, $metrics, $dimensions);

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $requestBody = json_decode($lastRequest->getBody()->getContents(), true);

        $this->assertEquals($metrics[0], $requestBody['metrics'][0]['name']);
        $this->assertEquals($dimensions[0], $requestBody['dimensions'][0]['name']);
        $this->assertEquals('30daysAgo', $requestBody['dateRanges'][0]['startDate']);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testGetMetadataSuccess(): void
    {
        $responseData = ['dimensions' => [], 'metrics' => []];
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => 'abc'])),
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new AnalyticsDataApi($this->redirectUrl, $this->clientId, $this->clientSecret, $this->refreshToken, $this->userId, guzzleClient: $guzzle);
        $result = $client->getMetadata($this->propertyId);

        $this->assertEquals($responseData, $result);
        $this->assertEquals("https://analyticsdata.googleapis.com/v1beta/properties/{$this->propertyId}/metadata", (string)$mock->getLastRequest()->getUri());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testBatchRunReportsSuccess(): void
    {
        $responseData = ['reports' => [[]]];
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => 'abc'])),
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new AnalyticsDataApi($this->redirectUrl, $this->clientId, $this->clientSecret, $this->refreshToken, $this->userId, guzzleClient: $guzzle);
        $requests = [['dimensions' => [['name' => 'city']]]];
        $result = $client->batchRunReports($this->propertyId, $requests);

        $this->assertEquals($responseData, $result);
        $this->assertEquals("https://analyticsdata.googleapis.com/v1beta/properties/{$this->propertyId}:batchRunReports", (string)$mock->getLastRequest()->getUri());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testRunAllReportsSuccess(): void
    {
        $response1 = [
            'rows' => array_fill(0, 5, ['dimensionValues' => [], 'metricValues' => []]),
            'rowCount' => 10
        ];
        $response2 = [
            'rows' => array_fill(0, 5, ['dimensionValues' => [], 'metricValues' => []]),
            'rowCount' => 10
        ];

        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => 'abc'])),
            new Response(200, [], json_encode($response1)),
            new Response(200, [], json_encode($response2))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new AnalyticsDataApi($this->redirectUrl, $this->clientId, $this->clientSecret, $this->refreshToken, $this->userId, guzzleClient: $guzzle);
        $result = $client->runAllReports($this->propertyId, ['limit' => 5]);

        $this->assertCount(10, $result['rows']);
        $this->assertEquals(0, $mock->count()); // All responses consumed (2 requests + 1 refresh)
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testRunAllReportsAndProcessSuccess(): void
    {
        $response1 = ['rows' => array_fill(0, 5, []), 'rowCount' => 10];
        $response2 = ['rows' => array_fill(0, 5, []), 'rowCount' => 10];

        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => 'abc'])),
            new Response(200, [], json_encode($response1)),
            new Response(200, [], json_encode($response2))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new AnalyticsDataApi($this->redirectUrl, $this->clientId, $this->clientSecret, $this->refreshToken, $this->userId, guzzleClient: $guzzle);
        
        $called = 0;
        $client->runAllReportsAndProcess($this->propertyId, ['limit' => 5], function($rows) use (&$called) {
            $called++;
            $this->assertCount(5, $rows);
        });

        $this->assertEquals(2, $called);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testRunAllReportsEmpty(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => 'abc'])),
            new Response(200, [], json_encode(['rows' => [], 'rowCount' => 0])),
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new AnalyticsDataApi($this->redirectUrl, $this->clientId, $this->clientSecret, $this->refreshToken, $this->userId, guzzleClient: $guzzle);
        $result = $client->runAllReports($this->propertyId, []);

        $this->assertCount(0, $result['rows']);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testRunAllReportsErrorMidLoop(): void
    {
        $response1 = ['rows' => array_fill(0, 5, []), 'rowCount' => 10];

        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => 'abc'])),
            new Response(200, [], json_encode($response1)),
            new Response(500, [], 'Internal Server Error'),
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new AnalyticsDataApi($this->redirectUrl, $this->clientId, $this->clientSecret, $this->refreshToken, $this->userId, guzzleClient: $guzzle);

        $this->expectException(ApiRequestException::class);

        $client->runAllReportsAndProcess($this->propertyId, ['limit' => 5], function($rows) {});
    }
}
