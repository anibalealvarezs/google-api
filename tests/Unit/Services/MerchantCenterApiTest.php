<?php

namespace Tests\Unit\Services;

use Anibalealvarezs\GoogleApi\Services\MerchantCenter\MerchantCenterApi;
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

class MerchantCenterApiTest extends TestCase
{
    protected Generator $faker;
    protected string $redirectUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $refreshToken;
    protected string $userId;
    protected array $scopes;
    protected string $token;
    protected string $merchantId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
        $this->redirectUrl = 'https://example.com/callback';
        $this->clientId = $this->faker->uuid;
        $this->clientSecret = $this->faker->uuid;
        $this->refreshToken = $this->faker->uuid;
        $this->userId = $this->faker->userName;
        $this->scopes = ['https://www.googleapis.com/auth/content'];
        $this->token = $this->faker->uuid;
        $this->merchantId = '123456789';
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
        $client = new MerchantCenterApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            scopes: $this->scopes,
            token: $this->token
        );

        $this->assertEquals('https://shoppingcontent.googleapis.com/content/v2.1/', $client->getBaseUrl());
        $this->assertEquals($this->scopes, $client->getScopes());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testSearchReportSuccess(): void
    {
        $responseData = [
            'results' => [
                [
                    'product_view' => ['id' => '123'],
                    'metrics' => ['clicks' => '10']
                ]
            ]
        ];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new MerchantCenterApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $query = "SELECT product_view.id, metrics.clicks FROM product_performance_view";
        $result = $client->searchReport($this->merchantId, $query);

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals("https://shoppingcontent.googleapis.com/content/v2.1/{$this->merchantId}/reports/search", (string)$lastRequest->getUri());
        
        $requestBody = json_decode($lastRequest->getBody()->getContents(), true);
        $this->assertEquals($query, $requestBody['query']);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testSearchAllReportsSuccess(): void
    {
        $response1 = [
            'results' => [['product_view' => ['id' => '1']]],
            'nextPageToken' => 'token1'
        ];
        $response2 = [
            'results' => [['product_view' => ['id' => '2']]]
        ];

        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => 'abc'])),
            new Response(200, [], json_encode($response1)),
            new Response(200, [], json_encode($response2))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new MerchantCenterApi($this->redirectUrl, $this->clientId, $this->clientSecret, $this->refreshToken, $this->userId, guzzleClient: $guzzle);
        $result = $client->searchAllReports($this->merchantId, "SELECT ...");

        $this->assertCount(2, $result['results']);
        $this->assertEquals('1', $result['results'][0]['product_view']['id']);
        $this->assertEquals('2', $result['results'][1]['product_view']['id']);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testSearchAllReportsAndProcessSuccess(): void
    {
        $response1 = ['results' => [['id' => 1]], 'nextPageToken' => 't1'];
        $response2 = ['results' => [['id' => 2]]];

        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => 'abc'])),
            new Response(200, [], json_encode($response1)),
            new Response(200, [], json_encode($response2))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new MerchantCenterApi($this->redirectUrl, $this->clientId, $this->clientSecret, $this->refreshToken, $this->userId, guzzleClient: $guzzle);
        
        $called = 0;
        $client->searchAllReportsAndProcess($this->merchantId, "SELECT ...", function($results) use (&$called) {
            $called++;
            $this->assertCount(1, $results);
        });

        $this->assertEquals(2, $called);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testSearchAllReportsAndProcessErrorMidLoop(): void
    {
        $response1 = ['results' => [['id' => 1]], 'nextPageToken' => 't1'];

        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => 'abc'])),
            new Response(200, [], json_encode($response1)),
            new Response(500, [], 'Internal Server Error'),
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new MerchantCenterApi($this->redirectUrl, $this->clientId, $this->clientSecret, $this->refreshToken, $this->userId, guzzleClient: $guzzle);

        $this->expectException(ApiRequestException::class);

        $client->searchAllReportsAndProcess($this->merchantId, "SELECT ...", function($results) {});
    }
}
