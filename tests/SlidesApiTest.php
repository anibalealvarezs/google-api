<?php

namespace Tests\Anibalealvarezs\GoogleApi\Services\Slides;

use Anibalealvarezs\ApiSkeleton\Enums\AuthType;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Other\SolidFill;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Page;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Tables\TableCellProperties;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Text\TextStyle;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Presentations\Request\LayoutReference;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Presentations\Request\PageElementProperties;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Presentations\Request\Range;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Presentations\Request\TableRange;
use Anibalealvarezs\GoogleApi\Services\Slides\SlidesApi;
use Exception;
use Faker\Factory as Faker;
use Faker\Generator;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SlidesApiTest extends TestCase
{
    protected Generator $faker;
    protected string $redirectUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $refreshToken;
    protected string $userId;
    protected array $scopes;
    protected string $token;
    protected string $presentationId;
    protected string $pageId;
    protected string $objectId;
    protected array $presentationData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
        $this->redirectUrl = 'https://example.com/callback';
        $this->clientId = $this->faker->uuid;
        $this->clientSecret = $this->faker->uuid;
        $this->refreshToken = $this->faker->uuid;
        $this->userId = $this->faker->userName;
        $this->scopes = ['https://www.googleapis.com/auth/presentations', 'https://www.googleapis.com/auth/drive'];
        $this->token = $this->faker->uuid;
        $this->presentationId = $this->faker->uuid;
        $this->pageId = $this->faker->uuid;
        $this->objectId = $this->faker->uuid;
        $this->presentationData = [
            'presentationId' => $this->presentationId,
            'slides' => [['objectId' => $this->pageId]],
            'layouts' => [
                [
                    "objectId" => $this->faker->uuid,
                    "pageType" => 'LAYOUT',
                    "pageElements" => [],
                    "pageProperties" => [
                        "pageBackgroundFill" => [
                            "stretchedPictureFill" => [
                                "contentUrl" => 'https://example.com/image.png',
                                "size" => [
                                    "width" => ['magnitude' => 720.0, 'unit' => 'PT'],
                                    "height" => ['magnitude' => 540.0, 'unit' => 'PT']
                                ],
                            ]
                        ]
                    ],
                    'layoutProperties' => [
                        'masterObjectId' => $this->faker->uuid,
                        'name' => 'LAYOUT_1',
                        'displayName' => 'Layout 1',
                    ]
                ],
            ],
        ];
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
        $client = new SlidesApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            scopes: $this->scopes,
            token: $this->token
        );

        $this->assertEquals('https://slides.googleapis.com/v1/presentations/', $client->getBaseUrl());
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
    public function testConstructorWithEmptyClientId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unauthorized. No Client ID provided.');

        new SlidesApi(
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
    public function testConstructorWithDefaultScopes(): void
    {
        $client = new SlidesApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId
        );

        $expectedScopes = [
            'https://www.googleapis.com/auth/presentations',
            'https://www.googleapis.com/auth/drive',
            'https://www.googleapis.com/auth/spreadsheets'
        ];
        $this->assertEquals($expectedScopes, $client->getScopes());
        $this->assertEquals('https://slides.googleapis.com/v1/presentations/', $client->getBaseUrl());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testGetPresentationDataSuccess(): void
    {
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($this->presentationData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SlidesApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $result = $client->getPresentationData($this->presentationId);

        $this->assertEquals($this->presentationData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('GET', $lastRequest->getMethod());
        $this->assertEquals("https://slides.googleapis.com/v1/presentations/".$this->presentationId, (string)$lastRequest->getUri());
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testGetLayoutsDataSuccess(): void
    {
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($this->presentationData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SlidesApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $result = $client->getLayoutsData($this->presentationId);

        $this->assertEquals($this->presentationData['layouts'], $result);
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testGetLayoutsSuccess(): void
    {
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($this->presentationData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SlidesApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $layouts = $client->getLayouts($this->presentationId);

        $this->assertIsArray($layouts);
        $this->assertCount(1, $layouts);
        $this->assertInstanceOf(Page::class, $layouts[0]);
        $this->assertEquals($this->presentationData['layouts'][0]['objectId'], $layouts[0]->objectId);
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testCreatePresentationSuccess(): void
    {
        $responseData = [
            'presentationId' => $this->presentationId,
            'title' => 'Test Presentation'
        ];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SlidesApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $pageSize = [
            "width" => ['magnitude' => 720.0, 'unit' => 'PT'],
            "height" => ['magnitude' => 540.0, 'unit' => 'PT']
        ];
        $title = 'Test Presentation';

        $result = $client->createPresentation(
            presentationId: $this->presentationId,
            pageSize: $pageSize,
            title: $title
        );

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals('https://slides.googleapis.com/v1/presentations/', (string)$lastRequest->getUri());
        $requestBody = json_decode($lastRequest->getBody()->getContents(), true);
        $this->assertEquals($this->presentationId, $requestBody['presentationId']);
        $this->assertEquals($title, $requestBody['title']);
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testCreatePresentationWithInvalidPageSize(): void
    {
        $client = new SlidesApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid pageSize');

        $client->createPresentation(pageSize: ['width' => 720]); // Missing height
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testCreateSlideSuccess(): void
    {
        $responseData = ['replies' => [['createSlide' => ['slideId' => $this->pageId]]]];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SlidesApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $slideLayoutReference = new LayoutReference(layoutId:  $this->faker->uuid);

        $result = $client->createSlide(
            presentationId: $this->presentationId,
            objectId: $this->objectId,
            insertionIndex: 1,
            slideLayoutReference: $slideLayoutReference
        );

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals("https://slides.googleapis.com/v1/presentations/" . $this->presentationId . ":batchUpdate", (string)$lastRequest->getUri());
        $requestBody = json_decode($lastRequest->getBody()->getContents(), true);
        $this->assertArrayHasKey('createSlide', $requestBody['requests'][0]);
        $this->assertEquals($this->objectId, $requestBody['requests'][0]['createSlide']['objectId']);
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testCreateSlideWithCheckPresentationNoData(): void
    {
        $responseData = ['replies' => [['createSlide' => ['slideId' => $this->pageId]]]];
        $mock = new MockHandler([
            new Response(200, [], json_encode($this->presentationData)),
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SlidesApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            token: $this->faker->uuid,
            guzzleClient: $guzzle
        );

        $result = $client->createSlide(
            presentationId: $this->presentationId,
            checkPresentation: true
        );

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals("https://slides.googleapis.com/v1/presentations/" . $this->presentationId . ':batchUpdate', (string)$lastRequest->getUri());
        $requestBody = json_decode($lastRequest->getBody()->getContents(), true);
        $this->assertArrayHasKey('createSlide', $requestBody['requests'][0]);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testGetSlideSuccess(): void
    {
        $slideData = ['pageId' => $this->pageId, 'objectId' => $this->objectId];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($slideData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SlidesApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $result = $client->getSlide(
            pageId: $this->pageId,
            presentationId: $this->presentationId
        );

        $this->assertEquals($slideData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('GET', $lastRequest->getMethod());
        $this->assertEquals("https://slides.googleapis.com/v1/presentations/" . $this->presentationId . "/pages/" . $this->pageId, (string)$lastRequest->getUri());
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testInsertTextSuccess(): void
    {
        $responseData = ['replies' => [['insertText' => []]]];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SlidesApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $text = 'Test Text';
        $result = $client->insertText(
            objectId: $this->objectId,
            presentationId: $this->presentationId,
            text: $text
        );

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals("https://slides.googleapis.com/v1/presentations/" . $this->presentationId . ":batchUpdate", (string)$lastRequest->getUri());
        $requestBody = json_decode($lastRequest->getBody()->getContents(), true);
        $this->assertArrayHasKey('insertText', $requestBody['requests'][0]);
        $this->assertEquals($this->objectId, $requestBody['requests'][0]['insertText']['objectId']);
        $this->assertEquals($text, $requestBody['requests'][0]['insertText']['text']);
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testDeleteTextSuccess(): void
    {
        $responseData = ['replies' => [['deleteText' => []]]];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SlidesApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $range = new Range(
            startIndex: 0,
            endIndex: 5
        );
        $result = $client->deleteText(
            objectId: $this->objectId,
            presentationId: $this->presentationId,
            range: $range
        );

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals("https://slides.googleapis.com/v1/presentations/" . $this->presentationId . ":batchUpdate", (string)$lastRequest->getUri());
        $requestBody = json_decode($lastRequest->getBody()->getContents(), true);
        $this->assertArrayHasKey('deleteText', $requestBody['requests'][0]);
        $this->assertEquals($this->objectId, $requestBody['requests'][0]['deleteText']['objectId']);
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testUpdateTextSuccess(): void
    {
        $responseData = [
            'replies' => [
                [
                    'deleteText' => [
                        'objectId' => $this->objectId,
                    ]
                ],
                [
                    'insertText' => [
                        'objectId' => $this->objectId,
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

        $client = new SlidesApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $text = 'Updated Text';
        $range = new Range(
            startIndex: 0,
            endIndex: 5
        );
        $result = $client->updateText(
            objectId: $this->objectId,
            presentationId: $this->presentationId,
            text: $text,
            range: $range
        );

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals("https://slides.googleapis.com/v1/presentations/" . $this->presentationId . ":batchUpdate", (string)$lastRequest->getUri());
        $requestBody = json_decode($lastRequest->getBody()->getContents(), true);
        $this->assertArrayHasKey('deleteText', $requestBody['requests'][0]);
        $this->assertArrayHasKey('insertText', $requestBody['requests'][0]);
        $this->assertEquals($this->objectId, $requestBody['requests'][0]['deleteText']['objectId']);
        $this->assertEquals($this->objectId, $requestBody['requests'][0]['insertText']['objectId']);
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testUpdateTextStyleSuccess(): void
    {
        $responseData = ['replies' => [['updateTextStyle' => []]]];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SlidesApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $style = new TextStyle(
            bold: true,
        );
        $range = new Range(
            startIndex: 0,
            endIndex: 5
        );
        $result = $client->updateTextStyle(
            objectId: $this->objectId,
            presentationId: $this->presentationId,
            style: $style,
            textRange: $range
        );

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $requestBody = json_decode($lastRequest->getBody()->getContents(), true);
        $this->assertArrayHasKey('updateTextStyle', $requestBody['requests'][0]);
        $this->assertEquals($this->objectId, $requestBody['requests'][0]['updateTextStyle']['objectId']);
        $this->assertTrue($requestBody['requests'][0]['updateTextStyle']['style']['bold']);
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testCreateTableSuccess(): void
    {
        $responseData = ['replies' => [['createTable' => ['objectId' => $this->objectId]]]];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SlidesApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $elementProperties = new PageElementProperties(pageObjectId: $this->pageId);
        $result = $client->createTable(
            rows: 2,
            columns: 3,
            elementProperties: $elementProperties,
            objectId: $this->objectId,
            presentationId: $this->presentationId
        );

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $requestBody = json_decode($lastRequest->getBody()->getContents(), true);
        $this->assertArrayHasKey('createTable', $requestBody['requests'][0]);
        $this->assertEquals(2, $requestBody['requests'][0]['createTable']['rows']);
        $this->assertEquals(3, $requestBody['requests'][0]['createTable']['columns']);
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testUpdateTableCellPropertiesSuccess(): void
    {
        $responseData = ['replies' => [['updateTableCellProperties' => []]]];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SlidesApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $tableCellProperties = new TableCellProperties(
            tableCellBackgroundFill: [
                'solidFill' => new SolidFill(
                    color: [
                        'rgbColor' => [
                            'red' => 1.0,
                            'green' => 0.0,
                            'blue' => 0.0
                        ]
                    ],
                    alpha: 1.0
                )
            ],
            contentAlignment: 'TOP',
        );
        $tableRange = new TableRange(['rowIndex' => 0, 'columnIndex' => 1]);
        $result = $client->updateTableCellProperties(
            objectId: $this->objectId,
            tableCellProperties: $tableCellProperties,
            presentationId: $this->presentationId,
            tableRange: $tableRange
        );

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $requestBody = json_decode($lastRequest->getBody()->getContents(), true);
        $this->assertArrayHasKey('updateTableCellProperties', $requestBody['requests'][0]);
        $this->assertEquals($this->objectId, $requestBody['requests'][0]['updateTableCellProperties']['objectId']);
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testCreateSheetsChartSuccess(): void
    {
        $responseData = ['replies' => [['createSheetsChart' => ['objectId' => $this->objectId]]]];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SlidesApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $elementProperties = new PageElementProperties(pageObjectId: $this->pageId);
        $spreadsheetId = $this->faker->uuid;
        $chartId = 123;

        $result = $client->createSheetsChart(
            elementProperties: $elementProperties,
            spreadsheetId: $spreadsheetId,
            chartId: $chartId,
            presentationId: $this->presentationId
        );

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $requestBody = json_decode($lastRequest->getBody()->getContents(), true);
        $this->assertArrayHasKey('createSheetsChart', $requestBody['requests'][0]);
        $this->assertEquals($spreadsheetId, $requestBody['requests'][0]['createSheetsChart']['spreadsheetId']);
        $this->assertEquals($chartId, $requestBody['requests'][0]['createSheetsChart']['chartId']);
        $this->assertEquals($newToken, $client->getToken());
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function testCreateImageSuccess(): void
    {
        $responseData = ['replies' => [['createImage' => ['objectId' => $this->objectId]]]];
        $newToken = $this->faker->uuid;
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => $newToken])), // Token refresh response
            new Response(200, [], json_encode($responseData))
        ]);
        $guzzle = $this->createMockedGuzzleClient(mock: $mock);

        $client = new SlidesApi(
            redirectUrl: $this->redirectUrl,
            clientId: $this->clientId,
            clientSecret: $this->clientSecret,
            refreshToken: $this->refreshToken,
            userId: $this->userId,
            guzzleClient: $guzzle
        );

        $elementProperties = new PageElementProperties(pageObjectId: $this->pageId);
        $url = 'https://example.com/image.jpg';

        $result = $client->createImage(
            elementProperties: $elementProperties,
            url: $url,
            presentationId: $this->presentationId,
            objectId: $this->objectId
        );

        $this->assertEquals($responseData, $result);
        $lastRequest = $mock->getLastRequest();
        $requestBody = json_decode($lastRequest->getBody()->getContents(), true);
        $this->assertArrayHasKey('createImage', $requestBody['requests'][0]);
        $this->assertEquals($url, $requestBody['requests'][0]['createImage']['url']);
        $this->assertEquals($this->objectId, $requestBody['requests'][0]['createImage']['objectId']);
        $this->assertEquals($newToken, $client->getToken());
    }
}
