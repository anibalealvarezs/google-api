<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Import or require your SheetsApi class and dependencies here
// require_once __DIR__ . '/../src/SheetsApi.php';

use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\ExtendedValue;
use Anibalealvarezs\GoogleApi\Services\Sheets\SheetsApi;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Yaml\Yaml;

// Load config
$config = Yaml::parseFile(__DIR__ . '/../config/config.yaml');

// Destination spreadsheet ID
$destinySpreadsheetId = '1fhaXZokkW3qpWEiwKuyzQN4rTmFJFWALVBiN3I1UGsU'; // Replace with your destination spreadsheet ID

// Instantiate SheetsApi
$sheetsApi = new SheetsApi(
    redirectUrl: $config['google_redirect_uri'],
    clientId: $config['google_client_id'],
    clientSecret: $config['google_client_secret'],
    refreshToken: $config['google_refresh_token'],
    userId: $config['google_user_id'],
);

$responses = [];

// PROCESS SCHEDULES

$sourceSpreadsheetId = '1EWqaxQ5CeuRFLqAqGZv2S9PWcmzFVi_ckXTpU6u3jj4';
$destSheetId = '1094456634';
$destTitle = 'A';
$maxRowIndex = 30000;
$sourceTitle = 'A';
$sourceEndColumn = 'M';

list($processedData, $startRowIndex, $responses) = prepareSection(
    sheetsApi: $sheetsApi,
    destinySpreadsheetId: $destinySpreadsheetId,
    sourceSpreadsheetId: $sourceSpreadsheetId,
    destSheetTitle: $destTitle,
    sourceSheetTitle: $sourceTitle,
    sourceEndColumn: $sourceEndColumn,
    maxRowIndex: $maxRowIndex,
    responses: $responses
);

$formattedData = [];
foreach($processedData as $index => $row) {
    $formattedRow = [
        'values' => []
    ];
    foreach ($row as $key => $cell) {
        $formattedRow['values'][] = match($key) {
            7 => [
                'userEnteredValue' => new ExtendedValue(
                    numberValue: floatval(str_replace([' €', ','], ['', '.'], $cell))
                )
            ],
            default => [
                'userEnteredValue' => new ExtendedValue(
                    stringValue: $cell
                )
            ],
        };
    }

    // Calculate the actual human row where this row will be written
    $humanRow = $startRowIndex + $index;

    $formattedRow['values'][] = [
        'userEnteredValue' => new ExtendedValue(
            formulaValue: '=IF(A' . $humanRow . '<>"";TEXT(A' . $humanRow . ';"yyyy-mm");"")'
        )
    ];
    $formattedData[] = $formattedRow;
}

list($numRowsToWrite, $numColsToWrite, $responses) = computeWriteDimensionsFromFormattedData($formattedData, $sheetsApi,
    $destinySpreadsheetId, $startRowIndex, $destTitle, $destSheetId, $responses);

// PROCESS LEADS

$sourceSpreadsheetId = '1C9905fM7dovoFl8y-qIX-WjoJ4bIKE18ItaKJ9Hbf3w';
$destSheetId = '70085298';
$destTitle = 'B';
$maxRowIndex = 40000;
$sourceTitle = 'Leads';
$sourceEndColumn = 'AB';

list($processedData, $startRowIndex, $responses) = prepareSection(
    sheetsApi: $sheetsApi,
    destinySpreadsheetId: $destinySpreadsheetId,
    sourceSpreadsheetId: $sourceSpreadsheetId,
    destSheetTitle: $destTitle,
    sourceSheetTitle: $sourceTitle,
    sourceEndColumn: $sourceEndColumn,
    maxRowIndex: $maxRowIndex,
    responses: $responses
);

$formattedData = [];
foreach($processedData as $index => $row) {
    $formattedRow = [
        'values' => []
    ];
    foreach ($row as $key => $cell) {
        $formattedRow['values'][] = match($key) {
            9 => [
                'userEnteredValue' => new ExtendedValue(
                    numberValue: floatval(str_replace([' €', ','], ['', '.'], $cell))
                )
            ],
            default => [
                'userEnteredValue' => new ExtendedValue(
                    stringValue: $cell
                )
            ],
        };
    }

    // Calculate the actual human row where this row will be written
    $humanRow = $startRowIndex + $index;

    $formattedRow['values'][] = [
        'userEnteredValue' => new ExtendedValue(
            formulaValue: '=IF(A' . $humanRow . '<>"";TEXT(A' . $humanRow . ';"yyyy-mm");"")'
        )
    ];
    $formattedData[] = $formattedRow;
}

// compute write dimensions from formatted data
list($numRowsToWrite, $numColsToWrite, $responses) = computeWriteDimensionsFromFormattedData($formattedData, $sheetsApi,
    $destinySpreadsheetId, $startRowIndex, $destTitle, $destSheetId, $responses);

// Print the result
header('Content-Type: application/json');
echo json_encode($responses, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

// FUNCTIONS

/**
 * Read destination last row, read source from next row, remove header and return prepared values.
 *
 * @param SheetsApi $sheetsApi
 * @param string $destinySpreadsheetId
 * @param string $sourceSpreadsheetId
 * @param string $destSheetTitle
 * @param string $sourceSheetTitle
 * @param string $sourceEndColumn
 * @param int $maxRowIndex
 * @param array $responses
 * @return array [processedData, startRowIndex, currentIndex, responses]
 * @throws GuzzleException
 */
function prepareSection(
    SheetsApi $sheetsApi,
    string $destinySpreadsheetId,
    string $sourceSpreadsheetId,
    string $destSheetTitle,
    string $sourceSheetTitle,
    string $sourceEndColumn,
    int $maxRowIndex,
    array $responses
): array {
    // Get the last used row count in the destination sheet (1 header + N rows => count = header + data)
    $lastRowData = $sheetsApi->readCells(
        spreadsheetId: $destinySpreadsheetId,
        sheetTitle: $destSheetTitle,
        endColumnIndex: 1,
        endRowIndex: $maxRowIndex,
    );
    $lastRowIndex = count($lastRowData['values']); // number of rows present (header + data)

    // Read source starting at the next zero-based row (to read from the human row lastRowIndex + 1)
    $data = $sheetsApi->readCells(
        spreadsheetId: $sourceSpreadsheetId,
        sheetTitle: $sourceSheetTitle,
        startRowIndex: $lastRowIndex, // zero-based: 11 -> starts at human row 12
        endColumnIndex: $sourceEndColumn,
        endRowIndex: $maxRowIndex,
    );

    $responses[] = ['content read for: ' . $sourceSpreadsheetId];

    // Remove header from the source read
    // $processedData = array_slice($data['values'], 1); // Headers no longer needed to be removed

    // Prepare write indexes
    $startRowIndex = $lastRowIndex;

    return [$data['values'], $startRowIndex, $responses];
}

// compute write dimensions from formatted data
/**
 * @param array $formattedData
 * @param SheetsApi $sheetsApi
 * @param string $destinySpreadsheetId
 * @param int $startRowIndex
 * @param string $destTitle
 * @param string $destSheetId
 * @param array $responses
 * @return array
 * @throws GuzzleException
 */
function computeWriteDimensionsFromFormattedData(
    array $formattedData,
    SheetsApi $sheetsApi,
    string $destinySpreadsheetId,
    int $startRowIndex,
    string $destTitle,
    string $destSheetId,
    array $responses
): array {
    $numRowsToWrite = count($formattedData);
    $numColsToWrite = count($formattedData[0]['values']);

// expand sheet if needed
    $responses[] = $sheetsApi->updateSheetProperties(
        spreadsheetId: $destinySpreadsheetId,
        gridProperties: [
            'rowCount' => $startRowIndex + $numRowsToWrite + 10,
            'columnCount' => $numColsToWrite + 10,
        ],
        title: $destTitle,
        sheetId: $destSheetId,
    );

// Write the copied data to the destination spreadsheet
    $responses[] = $sheetsApi->updateCells(
        spreadsheetId: $destinySpreadsheetId,
        rows: $formattedData,
        sheetId: $destSheetId,
        startRowIndex: $startRowIndex,
        endColumnIndex: $numColsToWrite,
        endRowIndex: $startRowIndex + $numRowsToWrite,
    );
    return array($numRowsToWrite, $numColsToWrite, $responses);
}
