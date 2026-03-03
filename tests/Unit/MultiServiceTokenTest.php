<?php

namespace Tests\Unit;

use Anibalealvarezs\GoogleApi\Services\Sheets\SheetsApi;
use Anibalealvarezs\GoogleApi\Services\Slides\SlidesApi;
use PHPUnit\Framework\TestCase;
use Faker\Factory as Faker;
use Faker\Generator;

class MultiServiceTokenTest extends TestCase
{
    protected Generator $faker;
    protected string $redirectUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $refreshToken;
    protected string $userId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
        $this->redirectUrl = 'https://example.com/callback';
        $this->clientId = $this->faker->uuid;
        $this->clientSecret = $this->faker->uuid;
        $this->refreshToken = $this->faker->uuid;
        $this->userId = $this->faker->userName;
    }

    public function testSeparateTokensPerService(): void
    {
        $tokenPath = __DIR__ . '/multi_token_test.json';
        if (file_exists($tokenPath)) {
            unlink($tokenPath);
        }

        $sheetsToken = 'sheets-token-' . $this->faker->uuid;
        $slidesToken = 'slides-token-' . $this->faker->uuid;

        // 1. Create Sheets client and set token
        $sheetsClient = new SheetsApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            tokenPath: $tokenPath
        );
        $sheetsClient->setToken($sheetsToken);

        // 2. Create Slides client and set token
        $slidesClient = new SlidesApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            tokenPath: $tokenPath
        );
        $slidesClient->setToken($slidesToken);

        // 3. Verify file structure
        $this->assertFileExists($tokenPath);
        $data = json_decode(file_get_contents($tokenPath), true);

        $this->assertArrayHasKey($this->userId, $data);
        $this->assertArrayHasKey('Services\Sheets\SheetsApi', $data[$this->userId]);
        $this->assertArrayHasKey('Services\Slides\SlidesApi', $data[$this->userId]);

        $this->assertEquals($sheetsToken, $data[$this->userId]['Services\Sheets\SheetsApi']);
        $this->assertEquals($slidesToken, $data[$this->userId]['Services\Slides\SlidesApi']);

        // 4. Verify loading independent tokens
        $newSheetsClient = new SheetsApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            tokenPath: $tokenPath
        );
        $this->assertEquals($sheetsToken, $newSheetsClient->getToken());

        $newSlidesClient = new SlidesApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            tokenPath: $tokenPath
        );
        $this->assertEquals($slidesToken, $newSlidesClient->getToken());

        unlink($tokenPath);
    }
}
