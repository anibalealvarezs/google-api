<?php

namespace Tests\Unit\Services;

use Anibalealvarezs\GoogleApi\Services\BusinessInformation\BusinessInformationApi;
use Exception;
use Faker\Factory as Faker;
use Faker\Generator;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class BusinessInformationApiTest extends TestCase
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
        $this->scopes = ['https://www.googleapis.com/auth/business.manage'];
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
        $client = new BusinessInformationApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            scopes: $this->scopes,
            token: $this->token
        );

        $this->assertEquals('https://mybusinessbusinessinformation.googleapis.com/v1/', $client->getBaseUrl());
        $this->assertEquals($this->scopes, $client->getScopes());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testGetAccountsSuccess(): void
    {
        $responseData = [
            'accounts' => [
                ['name' => 'accounts/123', 'accountName' => 'My Account', 'type' => 'PERSONAL']
            ]
        ];
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => 'new_token'])),
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new BusinessInformationApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $result = $client->getAccounts();

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('GET', $lastRequest->getMethod());
        $this->assertStringContainsString('accounts', (string)$lastRequest->getUri());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testGetLocationsSuccess(): void
    {
        $responseData = [
            'locations' => [
                ['name' => 'locations/loc123', 'title' => 'My Location']
            ]
        ];
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => 'new_token'])),
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new BusinessInformationApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $result = $client->getLocations('accounts/123');

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('GET', $lastRequest->getMethod());
        $this->assertStringContainsString('accounts/123/locations', (string)$lastRequest->getUri());
    }
}
