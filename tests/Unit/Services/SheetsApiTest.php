<?php

namespace Tests\Unit\Services;

use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\ValueInputOption;
use Anibalealvarezs\GoogleApi\Services\Sheets\SheetsApi;
use Exception;
use Faker\Factory as Faker;
use Faker\Generator;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class SheetsApiTest extends TestCase
{
    protected Generator $faker;
    protected string $redirectUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $refreshToken;
    protected string $userId;
    protected string $spreadsheetId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
        $this->redirectUrl = 'https://example.com/callback';
        $this->clientId = $this->faker->uuid;
        $this->clientSecret = $this->faker->uuid;
        $this->refreshToken = $this->faker->uuid;
        $this->userId = $this->faker->userName;
        $this->spreadsheetId = $this->faker->uuid;
    }

    protected function createMockedGuzzleClient(array $responses): GuzzleClient
    {
        $mock = new MockHandler($responses);
        $handler = HandlerStack::create($mock);
        return new GuzzleClient(['handler' => $handler]);
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function testCreateSpreadsheetSuccess(): void
    {
        $responseData = ['spreadsheetId' => 'new-id', 'properties' => ['title' => 'New Sheet']];
        $mock = new MockHandler([
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = new GuzzleClient(['handler' => HandlerStack::create($mock)]);

        $api = new SheetsApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            token: 'test-token',
            guzzleClient: $guzzle
        );

        $result = $api->createSpreadsheet(['properties' => ['title' => 'New Sheet']]);

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals('https://sheets.googleapis.com/v4/spreadsheets/', (string)$lastRequest->getUri());
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function testAppendCellsSuccess(): void
    {
        $responseData = ['updates' => ['updatedRange' => 'Sheet1!A1:B1']];
        $mock = new MockHandler([
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = new GuzzleClient(['handler' => HandlerStack::create($mock)]);

        $api = new SheetsApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            token: 'test-token',
            guzzleClient: $guzzle
        );

        $range = 'Sheet1!A1';
        $data = [['val1', 'val2']];
        $result = $api->appendCells($this->spreadsheetId, $data, $range);

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertStringContainsString('/values/' . $range . ':append', (string)$lastRequest->getUri());

        $body = json_decode($lastRequest->getBody()->getContents(), true);
        $this->assertEquals($data, $body['values']);
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function testReadMultipleCellsSuccess(): void
    {
        $responseData = ['spreadsheetId' => $this->spreadsheetId, 'valueRanges' => [['range' => 'A1:B2', 'values' => [['1']]]]];
        $mock = new MockHandler([
            new Response(200, [], json_encode(['valueRanges' => [['range' => 'A1:B2', 'values' => [['1']]]]]))
        ]);
        $guzzle = new GuzzleClient(['handler' => HandlerStack::create($mock)]);

        $api = new SheetsApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            token: 'test-token',
            guzzleClient: $guzzle
        );

        $ranges = ['Sheet1!A1:B2', 'Sheet2!C1:D2'];
        $result = $api->readMultipleCells($this->spreadsheetId, $ranges);

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('GET', $lastRequest->getMethod());
        $this->assertStringContainsString('/values:batchGet', (string)$lastRequest->getUri());

        $query = $lastRequest->getUri()->getQuery();
        foreach ($ranges as $range) {
            $this->assertStringContainsString(urlencode($range), $query);
        }
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function testUpdateMultipleCellsValuesSuccess(): void
    {
        $responseData = ['responses' => []];
        $mock = new MockHandler([
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = new GuzzleClient(['handler' => HandlerStack::create($mock)]);

        $api = new SheetsApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            token: 'test-token',
            guzzleClient: $guzzle
        );

        $request = [
            'valueInputOption' => ValueInputOption::RAW,
            'data' => [
                [
                    'range' => 'Sheet1!A1',
                    'values' => [['Updated']]
                ]
            ]
        ];

        $result = $api->updateMultipleCellsValues($this->spreadsheetId, $request);

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertStringContainsString('/values:batchUpdate', (string)$lastRequest->getUri());

        $body = json_decode($lastRequest->getBody()->getContents(), true);
        $this->assertEquals('RAW', $body['valueInputOption']);
        $this->assertEquals('Sheet1!A1', $body['data'][0]['range']);
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function testClearMultipleCellsSuccess(): void
    {
        $responseData = ['clearedRanges' => ['Sheet1!A1']];
        $mock = new MockHandler([
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = new GuzzleClient(['handler' => HandlerStack::create($mock)]);

        $api = new SheetsApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            token: 'test-token',
            guzzleClient: $guzzle
        );

        $ranges = ['Sheet1!A1'];
        $result = $api->clearMultipleCells($this->spreadsheetId, ['ranges' => $ranges]);

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertStringContainsString('/values:batchClear', (string)$lastRequest->getUri());

        $body = json_decode($lastRequest->getBody()->getContents(), true);
        $this->assertEquals($ranges, $body['ranges']);
    }
}
