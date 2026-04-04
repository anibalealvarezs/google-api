<?php

namespace Tests\Unit;

use Anibalealvarezs\ApiSkeleton\Enums\AuthType;
use Anibalealvarezs\GoogleApi\Google\GoogleApi;
use Exception;
use Faker\Factory as Faker;
use Faker\Generator;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class GoogleApiTest extends TestCase
{
    protected Generator $faker;
    protected string $baseUrl;
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
        $this->baseUrl = 'https://www.googleapis.com';
        $this->redirectUrl = 'https://example.com/callback';
        $this->clientId = $this->faker->uuid;
        $this->clientSecret = $this->faker->uuid;
        $this->refreshToken = $this->faker->uuid;
        $this->userId = $this->faker->userName;
        $this->scopes = ['https://www.googleapis.com/auth/drive.readonly'];
        $this->token = $this->faker->uuid;
    }

    protected function createMockedGuzzleClient(array $responses): GuzzleClient
    {
        $mock = new MockHandler($responses);
        $handler = HandlerStack::create($mock);
        return new GuzzleClient(['handler' => $handler]);
    }

    /**
     * @throws Exception
     */
    public function testConstructorWithValidParameters(): void
    {
        $client = new GoogleApi(
            baseUrl: $this->baseUrl,
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            scopes: $this->scopes,
            token: $this->token
        );

        $this->assertEquals($this->baseUrl, $client->getBaseUrl());
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
        $this->assertEquals(AuthType::oAuthV2, $client->getAuthType());
        $this->assertEquals(['location' => 'header', 'headerPrefix' => 'Bearer '], $client->getAuthSettings());
        $this->assertEquals(['Content-Type' => 'application/json'], $client->getHeaders());
        $this->assertInstanceOf(GuzzleClient::class, $client->getGuzzleClient());
    }

    /**
     * @throws Exception
     */
    public function testConstructorWithEmptyBaseUrl(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Base URL is required');

        new GoogleApi(
            baseUrl: '',
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            scopes: $this->scopes,
            token: $this->token
        );
    }

    /**
     * @throws Exception
     */
    public function testConstructorWithInvalidBaseUrl(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid baseUrl parameter: "invalid-url"');

        new GoogleApi(
            baseUrl: 'invalid-url',
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            scopes: $this->scopes,
            token: $this->token
        );
    }

    /**
     * @throws Exception
     */
    public function testConstructorWithEmptyClientId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unauthorized. No Client ID provided.');

        new GoogleApi(
            baseUrl: $this->baseUrl,
            redirectUrl: $this->redirectUrl,
            clientId: '',
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            scopes: $this->scopes,
            token: $this->token
        );
    }

    /**
     * @throws Exception
     */
    public function testConstructorWithEmptyClientSecret(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unauthorized. No Client Secret provided.');

        new GoogleApi(
            baseUrl: $this->baseUrl,
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: '',
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            scopes: $this->scopes,
            token: $this->token
        );
    }

    /**
     * @throws Exception
     */
    public function testConstructorWithEmptyRefreshToken(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unauthorized. No refresh token provided.');

        new GoogleApi(
            baseUrl: $this->baseUrl,
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: '',
            userId: $this->userId,
            scopes: $this->scopes,
            token: $this->token
        );
    }

    /**
     * @throws Exception
     */
    public function testConstructorWithInvalidRedirectUrl(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid redirectUrl parameter: "invalid-url"');

        new GoogleApi(
            baseUrl: $this->baseUrl,
            redirectUrl: 'invalid-url',
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            scopes: $this->scopes,
            token: $this->token
        );
    }

    /**
     * @throws Exception
     */
    public function testConstructorWithEmptyScopes(): void
    {
        $client = new GoogleApi(
            baseUrl: $this->baseUrl,
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            scopes: [],
            token: $this->token
        );

        $this->assertEquals([], $client->getScopes());
        $this->assertEquals($this->baseUrl, $client->getBaseUrl());
        $this->assertEquals($this->redirectUrl, $client->getRedirectUrl());
        $this->assertEquals($this->clientId, $client->getClientId());
        $this->assertEquals($this->clientSecret, $client->getClientSecret());
        $this->assertEquals($this->refreshToken, $client->getRefreshToken());
        $this->assertEquals($this->userId, $client->getUserId());
        $this->assertEquals($this->token, $client->getToken());
    }

    /**
     * @throws Exception
     */
    public function testConstructorWithEmptyToken(): void
    {
        $client = new GoogleApi(
            baseUrl: $this->baseUrl,
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            scopes: $this->scopes,
            token: ''
        );

        $this->assertEquals('', $client->getToken());
        $this->assertEquals($this->baseUrl, $client->getBaseUrl());
        $this->assertEquals($this->redirectUrl, $client->getRedirectUrl());
        $this->assertEquals($this->clientId, $client->getClientId());
        $this->assertEquals($this->clientSecret, $client->getClientSecret());
        $this->assertEquals($this->refreshToken, $client->getRefreshToken());
        $this->assertEquals($this->userId, $client->getUserId());
        $this->assertEquals($this->scopes, $client->getScopes());
    }

    /**
     * @throws Exception
     */
    public function testConstructorWithEmptyUserId(): void
    {
        $client = new GoogleApi(
            baseUrl: $this->baseUrl,
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: '',
            scopes: $this->scopes,
            token: $this->token
        );

        $this->assertEquals('', $client->getUserId());
        $this->assertEquals($this->baseUrl, $client->getBaseUrl());
        $this->assertEquals($this->redirectUrl, $client->getRedirectUrl());
        $this->assertEquals($this->clientId, $client->getClientId());
        $this->assertEquals($this->clientSecret, $client->getClientSecret());
        $this->assertEquals($this->refreshToken, $client->getRefreshToken());
        $this->assertEquals($this->scopes, $client->getScopes());
        $this->assertEquals($this->token, $client->getToken());
    }

    /**
     * @throws Exception
     */
    public function testTokenPersistence(): void
    {
        $tokenPath = __DIR__ . '/token_test.json';
        if (file_exists($tokenPath)) {
            unlink($tokenPath);
        }

        $newToken = 'new-access-token';

        $client = new GoogleApi(
            baseUrl: $this->baseUrl,
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            scopes: $this->scopes,
            tokenPath: $tokenPath
        );

        $client->setToken($newToken);

        $this->assertFileExists($tokenPath);
        $data = json_decode(json: (string) file_get_contents($tokenPath), associative: true);
        $expectedKey = 'RefreshToken_' . substr(md5($this->refreshToken), 0, 16);
        $this->assertEquals($newToken, $data[$this->userId][$expectedKey]);

        // Test loading
        $newClient = new GoogleApi(
            baseUrl: $this->baseUrl,
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            scopes: $this->scopes,
            tokenPath: $tokenPath
        );

        $this->assertEquals($newToken, $newClient->getToken());

        unlink($tokenPath);
    }

    /**
     * @throws Exception
     */
    public function testTokenLoadingFromFileAndNoRefresh(): void
    {
        $tokenPath = __DIR__ . '/token_load_test.json';
        $storedToken = 'stored-token-from-file';
        $expectedKey = 'RefreshToken_' . substr(md5($this->refreshToken), 0, 16);
        file_put_contents($tokenPath, json_encode([$this->userId => [$expectedKey => $storedToken]], JSON_PRETTY_PRINT));

        // Mock a single successful API response
        $mock = new MockHandler([
            new \GuzzleHttp\Psr7\Response(200, [], json_encode(['success' => true]))
        ]);
        $container = [];
        $history = \GuzzleHttp\Middleware::history($container);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);
        $guzzleClient = new GuzzleClient(['handler' => $handlerStack]);

        $client = new GoogleApi(
            baseUrl: $this->baseUrl,
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            scopes: $this->scopes,
            guzzleClient: $guzzleClient,
            tokenPath: $tokenPath
        );

        // 1. Verify token was correctly loaded on instantiation
        $this->assertEquals($storedToken, $client->getToken());

        // 2. Perform a request
        $client->performRequest('GET', '/test-endpoint');

        // 3. Verify exactly 1 request was made (no refresh request)
        $this->assertCount(1, $container);
        $request = $container[0]['request'];

        // 4. Verify the request used the token from the file
        $this->assertEquals('Bearer ' . $storedToken, $request->getHeaderLine('Authorization'));
        $this->assertStringContainsString('/test-endpoint', (string) $request->getUri());

        unlink($tokenPath);
    }
}
