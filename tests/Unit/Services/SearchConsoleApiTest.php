<?php

namespace Tests\Unit\Services;

use Anibalealvarezs\ApiSkeleton\Classes\Exceptions\ApiRequestException;
use Anibalealvarezs\GoogleApi\Services\SearchConsole\SearchConsoleApi;
use Exception;
use Faker\Factory as Faker;
use Faker\Generator;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SearchConsoleApiTest extends TestCase
{
    protected Generator $faker;
    protected string $redirectUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $refreshToken;
    protected string $userId;
    protected array $scopes;
    protected string $token;
    protected string $siteUrl;
    protected string $sitemapUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
        $this->redirectUrl = 'https://example.com/callback';
        $this->clientId = $this->faker->uuid;
        $this->clientSecret = $this->faker->uuid;
        $this->refreshToken = $this->faker->uuid;
        $this->userId = $this->faker->userName;
        $this->scopes = ['https://www.googleapis.com/auth/webmasters'];
        $this->token = $this->faker->uuid;
        $this->siteUrl = 'https://example.com';
        $this->sitemapUrl = 'https://example.com/sitemap.xml';
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
        $client = new SearchConsoleApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            scopes: $this->scopes,
            token: $this->token
        );

        $this->assertEquals('https://www.googleapis.com/webmasters/v3/', $client->getBaseUrl());
        $this->assertEquals('https://accounts.google.com/o/oauth2/auth', $client->getAuthUrl());
        $this->assertEquals('https://oauth2.googleapis.com/token', $client->getTokenUrl());
        $this->assertEquals('https://accounts.google.com/o/oauth2/auth?access_type=offline', $client->getRefreshAuthUrl());
        $this->assertEquals($this->redirectUrl, $client->getRedirectUrl());
        $this->assertEquals($this->clientId, $client->getClientId());
        $this->assertEquals($this->clientSecret, $client->getClientSecret());
        $this->assertEquals($this->refreshToken, $client->getRefreshToken());
        $this->assertEquals($this->userId, $client->getUserId());
        $this->assertEquals($this->scopes, $client->getScopes());
        $this->assertEquals($this->token, $client->getToken());
        $this->assertEquals(['Content-Type' => 'application/json'], $client->getHeaders());
        $this->assertInstanceOf(GuzzleClient::class, $client->getGuzzleClient());
    }

    /**
     * @throws Exception
     */
    public function testConstructorWithEmptyClientId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unauthorized. No Client ID provided.');

        new SearchConsoleApi(
            redirectUrl: $this->redirectUrl,
            clientId: '',
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            scopes: $this->scopes
        );
    }

    /**
     * @throws Exception
     */
    public function testConstructorWithDefaultScopes(): void
    {
        $client = new SearchConsoleApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId
        );

        $expectedScopes = ['https://www.googleapis.com/auth/webmasters'];
        $this->assertEquals($expectedScopes, $client->getScopes());
        $this->assertEquals('https://www.googleapis.com/webmasters/v3/', $client->getBaseUrl());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testGetSearchQueryResultsSuccess(): void
    {
        $responseData = ['rows' => [['keys' => ['test'], 'clicks' => 100]]];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SearchConsoleApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $startDate = '2023-01-01';
        $endDate = '2023-01-31';
        $dimensions = ['query'];
        $result = $client->getSearchQueryResults(
            siteUrl: $this->siteUrl,
            startDate: $startDate,
            endDate: $endDate,
            dimensions: $dimensions
        );

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals("https://www.googleapis.com/webmasters/v3/sites/" . urlencode($this->siteUrl) . "/searchAnalytics/query", (string)$lastRequest->getUri());
        $requestBody = json_decode($lastRequest->getBody()->getContents(), true);
        $this->assertEquals($startDate, $requestBody['startDate']);
        $this->assertEquals($endDate, $requestBody['endDate']);
        $this->assertEquals($dimensions, $requestBody['dimensions']);
        $this->assertEquals(25000, $requestBody['rowLimit']);
        $this->assertEquals('ALL', $requestBody['dataState']);
        $this->assertEquals('AUTO', $requestBody['aggregationType']);
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testGetSearchQueryResultsInvalidSiteUrl(): void
    {
        $client = new SearchConsoleApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid site URL: invalid-url. It must comply with either of the following formats: https://example.com or sc-domain:example.com');

        $client->getSearchQueryResults(
            siteUrl: 'invalid-url',
            startDate: '2023-01-01',
            endDate: '2023-01-31'
        );
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testGetAllSearchQueryResultsSuccess(): void
    {
        $responseData1 = ['rows' => [['keys' => ['test1'], 'clicks' => 100]]];
        $responseData2 = ['rows' => []];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData1)),
            new Response(200, [], json_encode($responseData2))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SearchConsoleApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $result = $client->getAllSearchQueryResults(
            siteUrl: $this->siteUrl,
            startDate: '2023-01-01',
            endDate: '2023-01-31',
            rowLimit: 1
        );

        $this->assertEquals(['rows' => $responseData1['rows']], $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals("https://www.googleapis.com/webmasters/v3/sites/" . urlencode($this->siteUrl) . "/searchAnalytics/query", (string)$lastRequest->getUri());
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testGetSitesSuccess(): void
    {
        $responseData = ['siteEntry' => [['siteUrl' => $this->siteUrl]]];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SearchConsoleApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $result = $client->getSites();

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('GET', $lastRequest->getMethod());
        $this->assertEquals('https://www.googleapis.com/webmasters/v3/sites', (string)$lastRequest->getUri());
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testGetSiteSuccess(): void
    {
        $responseData = ['siteUrl' => $this->siteUrl, 'permissionLevel' => 'siteOwner'];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SearchConsoleApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $result = $client->getSite($this->siteUrl);

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('GET', $lastRequest->getMethod());
        $this->assertEquals('https://www.googleapis.com/webmasters/v3/sites/' . urlencode($this->siteUrl), (string)$lastRequest->getUri());
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testGetSiteInvalidSiteUrl(): void
    {
        $client = new SearchConsoleApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid site URL: invalid-url. It must comply with either of the following formats: https://example.com or sc-domain:example.com');

        $client->getSite('invalid-url');
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testAddSiteSuccess(): void
    {
        $responseData = ['siteUrl' => 'sc-domain:example.com'];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SearchConsoleApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $siteUrl = 'sc-domain:example.com';
        $result = $client->addSite($siteUrl);

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('PUT', $lastRequest->getMethod());
        $this->assertEquals('https://www.googleapis.com/webmasters/v3/sites/' . urlencode($siteUrl), (string)$lastRequest->getUri());
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testRemoveSiteSuccess(): void
    {
        $responseData = [];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SearchConsoleApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $result = $client->removeSite($this->siteUrl);

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('DELETE', $lastRequest->getMethod());
        $this->assertEquals('https://www.googleapis.com/webmasters/v3/sites/' . urlencode($this->siteUrl), (string)$lastRequest->getUri());
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testGetSitemapsSuccess(): void
    {
        $responseData = ['sitemap' => [['path' => $this->sitemapUrl]]];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SearchConsoleApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $result = $client->getSitemaps($this->siteUrl);

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('GET', $lastRequest->getMethod());
        $this->assertEquals('https://www.googleapis.com/webmasters/v3/sites/' . urlencode($this->siteUrl) . '/sitemaps', (string)$lastRequest->getUri());
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testGetSitemapSuccess(): void
    {
        $responseData = ['path' => $this->sitemapUrl];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SearchConsoleApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $result = $client->getSitemap($this->siteUrl, $this->sitemapUrl);

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('GET', $lastRequest->getMethod());
        $this->assertEquals('https://www.googleapis.com/webmasters/v3/sites/' . urlencode($this->siteUrl) . '/sitemaps/' . urlencode($this->sitemapUrl), (string)$lastRequest->getUri());
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testAddSitemapSuccess(): void
    {
        $responseData = ['path' => $this->sitemapUrl];
        $newToken = $this->faker->uuid;

        // Mock for sitemap URL check
        $sitemapMock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/xml'], '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>')
        ]);
        $sitemapGuzzle = $this->createMockedGuzzleClient(mock: $sitemapMock);

        // Mock for API requests
        $apiMock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData)) // API response
        ]);
        $apiGuzzle = $this->createMockedGuzzleClient(mock: $apiMock);

        $client = new SearchConsoleApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $apiGuzzle
        );

        $result = $client->addSitemap($this->siteUrl, $this->sitemapUrl, $sitemapGuzzle);

        // Verify sitemap request
        $sitemapRequest = $sitemapMock->getLastRequest();
        $this->assertNotNull($sitemapRequest, 'Sitemap request was not made');
        $this->assertEquals('GET', $sitemapRequest->getMethod());
        $this->assertEquals($this->sitemapUrl, (string)$sitemapRequest->getUri());

        // Verify API request
        $apiRequest = $apiMock->getLastRequest();
        $this->assertEquals('PUT', $apiRequest->getMethod());
        $this->assertEquals('https://www.googleapis.com/webmasters/v3/sites/' . urlencode($this->siteUrl) . '/sitemaps/' . urlencode($this->sitemapUrl), (string)$apiRequest->getUri());
        $this->assertEquals($responseData, $result);
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testAddSitemapInvalidSitemapUrl(): void
    {
        $invalidSitemapUrl = 'https://example.com/invalid.xml';
        $newToken = $this->faker->uuid;

        // Mock for sitemap URL check
        $sitemapMock = new MockHandler([
            new Response(200, ['Content-Type' => 'text/html'], '<html>Not a sitemap</html>')
        ]);
        $sitemapGuzzle = $this->createMockedGuzzleClient(mock: $sitemapMock);

        // Mock for API requests
        $apiMock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken]))
        ]);
        $apiGuzzle = $this->createMockedGuzzleClient(mock: $apiMock);

        $client = new SearchConsoleApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $apiGuzzle
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid sitemap URL: ' . $invalidSitemapUrl . '. Either the URL is not valid, or the URL is not a sitemap or the sitemap is not available.');

        $client->addSitemap($this->siteUrl, $invalidSitemapUrl, $sitemapGuzzle);

        // Verify sitemap request
        $sitemapRequest = $sitemapMock->getLastRequest();
        $this->assertNotNull($sitemapRequest, 'Sitemap request was not made');
        $this->assertEquals('GET', $sitemapRequest->getMethod());
        $this->assertEquals($invalidSitemapUrl, (string)$sitemapRequest->getUri());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testRemoveSitemapSuccess(): void
    {
        $responseData = [];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SearchConsoleApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $result = $client->removeSitemap($this->siteUrl, $this->sitemapUrl);

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('DELETE', $lastRequest->getMethod());
        $this->assertEquals('https://www.googleapis.com/webmasters/v3/sites/' . urlencode($this->siteUrl) . '/sitemaps/' . urlencode($this->sitemapUrl), (string)$lastRequest->getUri());
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testInspectUrlSuccess(): void
    {
        $responseData = ['inspectionResult' => ['indexStatus' => 'INDEXED']];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SearchConsoleApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $url = 'https://example.com/page';
        $languageCode = 'en-US';
        $result = $client->inspectUrl($this->siteUrl, $url, $languageCode);

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals('https://searchconsole.googleapis.com/v1/urlInspection/index:inspect', (string)$lastRequest->getUri());
        $requestBody = json_decode($lastRequest->getBody()->getContents(), true);
        $this->assertEquals($url, $requestBody['inspectionUrl']);
        $this->assertEquals($this->siteUrl, $requestBody['siteUrl']);
        $this->assertEquals($languageCode, $requestBody['languageCode']);
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testInspectUrlRestoresBaseUrl(): void
    {
        $responseData = ['inspectionResult' => ['indexStatus' => 'INDEXED']];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SearchConsoleApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $url = 'https://example.com/page';
        $client->inspectUrl($this->siteUrl, $url);

        $this->assertEquals('https://www.googleapis.com/webmasters/v3/', $client->getBaseUrl());
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testGuzzleExceptionHandling(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $this->faker->uuid])), // Token refresh response
            new RequestException('API error', new Request('GET', 'sites'))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SearchConsoleApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $this->expectException(ApiRequestException::class);
        $this->expectExceptionMessage('API error');

        $client->getSites();
    }
}
