<?php

namespace Tests\Unit\Services;

use Anibalealvarezs\GoogleApi\Services\BusinessPerformance\BusinessPerformanceApi;
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

class BusinessPerformanceApiTest extends TestCase
{
    protected Generator $faker;
    protected string $redirectUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $refreshToken;
    protected string $userId;
    protected array $scopes;
    protected string $token;
    protected string $locationName;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
        $this->redirectUrl = 'https://example.com/callback';
        $this->clientId = $this->faker->uuid;
        $this->clientSecret = $this->faker->uuid;
        $this->refreshToken = $this->faker->uuid;
        $this->userId = $this->faker->userName;
        $this->scopes = ['https://www.googleapis.com/auth/business.manage'];
        $this->token = $this->faker->uuid;
        $this->locationName = 'locations/123456789';
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
        $client = new BusinessPerformanceApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            scopes: $this->scopes,
            token: $this->token
        );

        $this->assertEquals('https://businessprofileperformance.googleapis.com/v1/', $client->getBaseUrl());
        $this->assertEquals($this->scopes, $client->getScopes());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testFetchDailyMetricsTimeSeriesSuccess(): void
    {
        $responseData = [
            'timeSeries' => [
                ['metric' => 'CALL_CLICKS', 'values' => [['value' => '10']]]
            ]
        ];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new BusinessPerformanceApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $metrics = ['CALL_CLICKS'];
        $startDate = '2023-01-01';
        $endDate = '2023-01-31';

        $result = $client->fetchDailyMetricsTimeSeries($this->locationName, $metrics, $startDate, $endDate);

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('GET', $lastRequest->getMethod());
        $this->assertStringContainsString($this->locationName . ':fetchDailyMetricsTimeSeries', (string)$lastRequest->getUri());
        $this->assertStringContainsString('dailyMetric%5B0%5D=CALL_CLICKS', (string)$lastRequest->getUri());
        $this->assertStringContainsString('dailyRange.startDate.year=2023', (string)$lastRequest->getUri());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testFetchDailyMetricsTimeSeriesForMultipleLocationsSuccess(): void
    {
        $response1 = ['timeSeries' => [['metric' => 'CALL_CLICKS']]];
        $response2 = ['timeSeries' => [['metric' => 'WEBSITE_CLICKS']]];

        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => 'abc'])),
            new Response(200, [], json_encode($response1)),
            new Response(200, [], json_encode($response2))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new BusinessPerformanceApi($this->redirectUrl, $this->clientId, $this->clientSecret, $this->refreshToken, $this->userId, guzzleClient: $guzzle);
        $locations = ['locations/1', 'locations/2'];
        $result = $client->fetchDailyMetricsTimeSeriesForMultipleLocations($locations, ['CALL_CLICKS'], '2023-01-01', '2023-01-31');

        $this->assertCount(2, $result);
        $this->assertEquals($response1, $result['locations/1']);
        $this->assertEquals($response2, $result['locations/2']);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testFetchDailyMetricsTimeSeriesAndProcessSuccess(): void
    {
        $response1 = ['timeSeries' => []];
        $response2 = ['timeSeries' => []];

        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => 'abc'])),
            new Response(200, [], json_encode($response1)),
            new Response(200, [], json_encode($response2))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new BusinessPerformanceApi($this->redirectUrl, $this->clientId, $this->clientSecret, $this->refreshToken, $this->userId, guzzleClient: $guzzle);
        
        $called = 0;
        $client->fetchDailyMetricsTimeSeriesAndProcess(['locations/1', 'locations/2'], ['CALL_CLICKS'], '2023-01-01', '2023-01-31', function($location, $data) use (&$called) {
            $called++;
            $this->assertStringContainsString('locations/', $location);
        });

        $this->assertEquals(2, $called);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testFetchDailyMetricsTimeSeriesAndProcessErrorMidLoop(): void
    {
        $response1 = ['timeSeries' => []];

        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => 'abc'])),
            new Response(200, [], json_encode($response1)),
            new Response(500, [], 'Internal Server Error'),
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new BusinessPerformanceApi($this->redirectUrl, $this->clientId, $this->clientSecret, $this->refreshToken, $this->userId, guzzleClient: $guzzle);

        $this->expectException(ApiRequestException::class);

        $client->fetchDailyMetricsTimeSeriesAndProcess(['locations/1', 'locations/2'], ['CALL_CLICKS'], '2023-01-01', '2023-01-31', function($location, $data) {});
    }
}
