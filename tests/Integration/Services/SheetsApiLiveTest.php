<?php

namespace Tests\Integration\Services;

use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Cells\ValueRange;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Cells\InsertDataOption;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Dimension;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\ValueInputOption;
use Anibalealvarezs\GoogleApi\Services\Sheets\Requests\Cells\BatchClearValuesRequest;
use Anibalealvarezs\GoogleApi\Services\Sheets\Requests\Cells\BatchUpdateValuesRequest;
use Anibalealvarezs\GoogleApi\Services\Sheets\SheetsApi;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class SheetsApiLiveTest extends TestCase
{
    protected SheetsApi $api;
    protected static ?string $spreadsheetId = null;
    protected static string $sheetTitle = 'Sheet1';

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $config = app_config();

        if (
            empty($config['google_client_id']) || 
            str_contains($config['google_client_id'], 'YOUR_') ||
            empty($config['google_refresh_token']) ||
            str_contains($config['google_refresh_token'], 'YOUR_')
        ) {
            $this->markTestSkipped('Google credentials not available or using placeholders.');
        }

        $this->api = new SheetsApi(
            redirectUrl: $config['google_redirect_uri'],
            clientId: $config['google_client_id'],
            clientSecret: $config['google_client_secret'],
            refreshToken: !empty($config['sheets_refresh_token']) ? $config['sheets_refresh_token'] : $config['google_refresh_token'],
            userId: $config['google_user_id'],
            scopes: ["https://www.googleapis.com/auth/spreadsheets"],
            token: !empty($config['sheets_token']) ? $config['sheets_token'] : ($config['google_token'] ?? ''),
            tokenPath: $config['google_token_path'] ?? ''
        );

        // We will create a spreadsheet for testing if not provided
        if (isset($config['sheets_test_spreadsheet_id']) && $config['sheets_test_spreadsheet_id']) {
            self::$spreadsheetId = $config['sheets_test_spreadsheet_id'];
        }
    }

    /**
     * @throws GuzzleException
     */
    public function testCreateSpreadsheet()
    {
        $title = 'SDK Test Spreadsheet ' . time();
        $response = $this->api->createSpreadsheet([
            'properties' => [
                'title' => $title
            ]
        ]);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('spreadsheetId', $response);
        $this->assertEquals($title, $response['properties']['title']);

        // Use this ID for subsequent tests in this run if no ID was provided in config
        if (!self::$spreadsheetId) {
            self::$spreadsheetId = $response['spreadsheetId'];
            if (isset($response['sheets'][0]['properties']['title'])) {
                self::$sheetTitle = $response['sheets'][0]['properties']['title'];
            }
        }
    }

    /**
     * @throws GuzzleException
     */
    public function testAppendCells()
    {
        if (!self::$spreadsheetId) {
            $this->markTestSkipped('No spreadsheet ID available for testing.');
        }

        $data = [
            ['Append Row 1 Col 1', 'Append Row 1 Col 2'],
            ['Append Row 2 Col 1', 'Append Row 2 Col 2']
        ];

        $response = $this->api->appendCells(
            spreadsheetId: self::$spreadsheetId,
            data: $data,
            range: self::$sheetTitle . '!A1'
        );

        $this->assertIsArray($response);
        $this->assertArrayHasKey('spreadsheetId', $response);
        $this->assertArrayHasKey('updates', $response);
    }

    /**
     * @throws GuzzleException
     */
    public function testReadMultipleCells()
    {
        if (!self::$spreadsheetId) {
            $this->markTestSkipped('No spreadsheet ID available for testing.');
        }

        $ranges = [self::$sheetTitle . '!A1:B1', self::$sheetTitle . '!A2:B2'];
        $response = $this->api->readMultipleCells(
            spreadsheetId: self::$spreadsheetId,
            ranges: $ranges
        );

        $this->assertIsArray($response);
        $this->assertArrayHasKey('spreadsheetId', $response);
        $this->assertArrayHasKey('valueRanges', $response);
        $this->assertCount(2, $response['valueRanges']);
    }

    /**
     * @throws GuzzleException
     */
    public function testUpdateMultipleCellsValues()
    {
        if (!self::$spreadsheetId) {
            $this->markTestSkipped('No spreadsheet ID available for testing.');
        }

        $request = [
            'valueInputOption' => ValueInputOption::RAW,
            'data' => [
                [
                    'range' => self::$sheetTitle . '!C1',
                    'values' => [['Batch Update 1']]
                ],
                [
                    'range' => self::$sheetTitle . '!C2',
                    'values' => [['Batch Update 2']]
                ]
            ]
        ];

        $response = $this->api->updateMultipleCellsValues(
            spreadsheetId: self::$spreadsheetId,
            request: $request
        );

        $this->assertIsArray($response);
        $this->assertArrayHasKey('spreadsheetId', $response);
        $this->assertArrayHasKey('responses', $response);
    }

    /**
     * @throws GuzzleException
     */
    public function testClearMultipleCells()
    {
        if (!self::$spreadsheetId) {
            $this->markTestSkipped('No spreadsheet ID available for testing.');
        }

        $ranges = [self::$sheetTitle . '!C1', self::$sheetTitle . '!C2'];
        $request = [
            'ranges' => $ranges
        ];

        $response = $this->api->clearMultipleCells(
            spreadsheetId: self::$spreadsheetId,
            request: $request
        );

        $this->assertIsArray($response);
        $this->assertArrayHasKey('spreadsheetId', $response);
        $this->assertArrayHasKey('clearedRanges', $response);
    }
}
