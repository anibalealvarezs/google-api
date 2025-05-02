<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets;

use Anibalealvarezs\GoogleApi\Google\GoogleApi;
use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Cells\ValueRange;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\DataSourceChartProperties;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\EmbeddedObjectBorder;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\DimensionRange;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\ColorStyle;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\EmbeddedObjectPosition;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\FilterSpec;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\GridCoordinate;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\GridRange;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\SortSpec;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\TextFormat;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Sheets\DimensionProperties;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Sheets\GridProperties;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Sheets\RowData;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Sheets\SheetProperties;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Spreadsheets\ChartSpec;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Spreadsheets\EmbeddedChart;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\ChartTypes;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\ClearSheetMethods;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\DateTimeRenderOption;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Dimension;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Spreadsheets\PasteOrientation;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Spreadsheets\PasteType;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\ValueInputOption;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\ValueRenderOption;
use Anibalealvarezs\GoogleApi\Services\Sheets\Requests\Sheets\DeleteDimensionRequest;
use Anibalealvarezs\GoogleApi\Services\Sheets\Requests\Sheets\UpdateCellsRequest;
use Anibalealvarezs\GoogleApi\Services\Sheets\Requests\Sheets\UpdateDimensionPropertiesRequest;
use Anibalealvarezs\GoogleApi\Services\Sheets\Requests\Sheets\UpdateSheetPropertiesRequest;
use Anibalealvarezs\GoogleApi\Services\Sheets\Requests\Spreadsheets\AddChartRequest;
use Anibalealvarezs\GoogleApi\Services\Sheets\Requests\Spreadsheets\AddSheetRequest;
use Anibalealvarezs\GoogleApi\Services\Sheets\Requests\Spreadsheets\CopyPasteRequest;
use Anibalealvarezs\GoogleApi\Services\Sheets\Requests\Spreadsheets\DeleteSheetRequest;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

class SheetsApi extends GoogleApi
{
    /**
     * @param string $redirectUrl
     * @param string $clientId
     * @param string $clientSecret
     * @param string $refreshToken
     * @param string $userId
     * @param array $scopes
     * @param string $token
     * @throws Exception
     */
    public function __construct(
        string $redirectUrl,
        string $clientId,
        string $clientSecret,
        string $refreshToken,
        string $userId,
        array $scopes = [],
        string $token = ""
    ) {
        parent::__construct(
            baseUrl: "https://sheets.googleapis.com/v4/spreadsheets/",
            redirectUrl: $redirectUrl,
            clientId: $clientId,
            clientSecret: $clientSecret,
            refreshToken: $refreshToken,
            userId: $userId,
            scopes: ($scopes ?: ["https://www.googleapis.com/auth/spreadsheets"]),
            token: $token
        );
    }

    /**
     * @param string $spreadsheetId
     * @param string $fields
     * @return array
     * @throws GuzzleException
     */
    public function getSpreadsheetData(
        string $spreadsheetId,
        string $fields = "sheets.properties"
    ): array {
        // Request the spreadsheet data
        $response = $this->performRequest(
            method: "GET",
            endpoint: $spreadsheetId,
            query: [
                "fields" => $fields
            ],
        );
        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $spreadsheetId
     * @param string $title
     * @param int|null $sheetId
     * @return array
     * @throws GuzzleException
     */
    public function createSheetAtStart(
        string $spreadsheetId,
        string $title = "New Sheet",
        int $sheetId = null,
    ): array {
        // Create a new sheet at index 0
        return $this->createSheet(
            spreadsheetId: $spreadsheetId,
            index: 0,
            title: $title,
            sheetId: $sheetId,
        );
    }

    /**
     * @param string $spreadsheetId
     * @param string $title
     * @param int|null $sheetId
     * @param array|null $spreadsheetData
     * @return array
     * @throws GuzzleException
     */
    public function createSheetAtTheEnd(
        string $spreadsheetId,
        string $title = "New Sheet",
        int $sheetId = null,
        array $spreadsheetData = null,
    ): array {
        // Get spreadsheet data if not provided
        if (is_null($spreadsheetData)) {
            $spreadsheetData = $this->getSpreadsheetData($spreadsheetId);
        }
        // Get the current number of sheets
        if (isset($spreadsheetData["sheets"])) {
            // Add the new sheet at the end
            return $this->createSheet(
                spreadsheetId: $spreadsheetId,
                index: count($spreadsheetData["sheets"]),
                title: $title,
                sheetId: $sheetId,
                spreadsheetData: $spreadsheetData,
            );
        }
        // If there are no sheets, return error
        return [
            'error' => 'Error getting spreadsheet\'s sheets'
        ];
    }

    /**
     * @param string $spreadsheetId
     * @param int $index
     * @param string $title
     * @param int|null $sheetId
     * @param array|null $spreadsheetData
     * @param bool $getRequestObjectOnly
     * @return array
     * @throws GuzzleException
     */
    public function createSheet(
        string $spreadsheetId,
        int $index,
        string $title = "New Sheet",
        int $sheetId = null,
        array $spreadsheetData = null,
        bool $getRequestObjectOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];
        // Get spreadsheet data if not provided
        if (is_null($spreadsheetData)) {
            $spreadsheetData = $this->getSpreadsheetData($spreadsheetId);
        }
        if (isset($spreadsheetData["sheets"])) {
            if ($sheetId) {
                // Check sheet ID
                $sheetId = Helpers::getFirstValid(
                    id: $sheetId,
                    ids: $this->getSheetsIdsFromSpreadsheetData($spreadsheetData)
                );
            }
            // Check sheet Title
            $title = Helpers::getFirstValid(
                id: $title,
                ids: $this->getSheetsTitlesFromSpreadsheetData($spreadsheetData)
            );
            // Set Sheet Properties
            $sheetProperties = new SheetProperties(
                title: $title,
                gridProperties: new GridProperties(),
                index: $index,
                sheetId: $sheetId,
            );
            // Get AddSheet request
            $addSheetRequest = [
                'addSheet' => Helpers::getJsonableArray(new AddSheetRequest(
                    properties: $sheetProperties
                ))
            ];
            // Return just the object if requested
            if ($getRequestObjectOnly) {
                return $addSheetRequest;
            }
            // Push requests
            $requests['requests'][] = $addSheetRequest;
            // Exec request
            $response = $this->performRequest(
                method: "POST",
                endpoint: $spreadsheetId . ':batchUpdate',
                body: json_encode($requests),
            );
            // Return response
            return json_decode($response->getBody()->getContents(), true);
        }
        // If there are no sheets, return error
        return [
            'error' => 'Error getting spreadsheet\'s sheets'
        ];
    }

    /**
     * @param string $spreadsheetId
     * @param array $sheetsData Array of sheets data where each element is an associative array with the following keys:
     *                          - 'title' (string, optional): The title of the sheet. Default is 'New Sheet {index}'.
     *                          - 'index' (int, optional): The index of the sheet. Default is the next available index.
     *                          - 'sheetId' (int, optional): The ID of the sheet. Default is null.
     * @param array|null $spreadsheetData
     * @param bool $getRequestObjectsOnly
     * @return array
     * @throws GuzzleException
     */
    public function createMultipleSheets(
        string $spreadsheetId,
        array $sheetsData,
        array $spreadsheetData = null,
        bool $getRequestObjectsOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];

        // Get the current spreadsheet data
        if (is_null($spreadsheetData)) {
            $spreadsheetData = $this->getSpreadsheetData($spreadsheetId);
        }

        // Get the current number of sheets
        $currentSheetCount = count($spreadsheetData['sheets']);

        // Iterate over sheets data to create requests
        foreach ($sheetsData as $index => $sheetData) {
            $title = $sheetData['title'] ?? 'New Sheet ' . ($currentSheetCount + $index + 1);
            $sheetIndex = $currentSheetCount + $index;
            $sheetId = $sheetData['sheetId'] ?? null;

            // Set Sheet Properties
            $sheetProperties = new SheetProperties(
                title: $title,
                gridProperties: new GridProperties(),
                index: $sheetIndex,
                sheetId: $sheetId,
            );

            // Get AddSheet request
            $addSheetRequest = [
                'addSheet' => Helpers::getJsonableArray(new AddSheetRequest(
                    properties: $sheetProperties
                ))
            ];

            // Push request to requests array
            $requests['requests'][] = $addSheetRequest;
        }
        // Return just the list of objects if requested
        if ($getRequestObjectsOnly) {
            return $requests['requests'];
        }

        // Exec request
        $response = $this->performRequest(
            method: "POST",
            endpoint: $spreadsheetId . ':batchUpdate',
            body: json_encode($requests),
        );

        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $spreadsheetId
     * @param int $sheetId
     * @param array|null $spreadsheetData
     * @param bool $getRequestObjectOnly
     * @return array
     * @throws GuzzleException
     */
    public function deleteSheet(
        string $spreadsheetId,
        int $sheetId,
        array $spreadsheetData = null,
        bool $getRequestObjectOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];
        // Get spreadsheet data if not provided
        if (is_null($spreadsheetData)) {
            $spreadsheetData = $this->getSpreadsheetData($spreadsheetId);
        }
        if (isset($spreadsheetData["sheets"])) {
            // Get DeleteSheet request
            $deleteSheetRequest = [
                'deleteSheet' => Helpers::getJsonableArray(new DeleteSheetRequest(
                    sheetId: $sheetId
                ))
            ];
            // Return just the object if requested
            if ($getRequestObjectOnly) {
                return $deleteSheetRequest;
            }
            // Push requests
            $requests['requests'][] = $deleteSheetRequest;
            // Exec request
            $response = $this->performRequest(
                method: "POST",
                endpoint: $spreadsheetId . ':batchUpdate',
                body: json_encode($requests),
            );
            // Return response
            return json_decode($response->getBody()->getContents(), true);
        }
        // If there are no sheets, return error
        return [
            'error' => 'Error getting spreadsheet\'s sheets'
        ];
    }

    /**
     * @param string $spreadsheetId
     * @param array $sheetIds Array of sheet IDs to be deleted.
     * @param array|null $spreadsheetData
     * @param bool $getRequestObjectsOnly
     * @return array
     * @throws GuzzleException
     */
    public function deleteMultipleSheets(
        string $spreadsheetId,
        array $sheetIds,
        array $spreadsheetData = null,
        bool $getRequestObjectsOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];

        // Get the current spreadsheet data if not provided
        if (is_null($spreadsheetData)) {
            $spreadsheetData = $this->getSpreadsheetData($spreadsheetId);
        }

        // Iterate over sheet IDs to create delete requests
        foreach ($sheetIds as $sheetId) {
            // Get DeleteSheet request
            $deleteSheetRequest = [
                'deleteSheet' => Helpers::getJsonableArray(new DeleteSheetRequest(
                    sheetId: $sheetId
                ))
            ];

            // Push request to requests array
            $requests['requests'][] = $deleteSheetRequest;
        }

        // Return just the list of objects if requested
        if ($getRequestObjectsOnly) {
            return $requests['requests'];
        }

        // Exec request
        $response = $this->performRequest(
            method: "POST",
            endpoint: $spreadsheetId . ':batchUpdate',
            body: json_encode($requests),
        );

        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $spreadsheetId
     * @param array $keepSheetIds Array of sheet IDs to keep.
     * @param array|null $spreadsheetData
     * @param bool $getRequestObjectsOnly
     * @return array
     * @throws GuzzleException
     */
    public function deleteAllSheetsBut(
        string $spreadsheetId,
        array $keepSheetIds,
        array $spreadsheetData = null,
        bool $getRequestObjectsOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];

        // Get the current spreadsheet data if not provided
        if (is_null($spreadsheetData)) {
            $spreadsheetData = $this->getSpreadsheetData($spreadsheetId);
        }

        // Get the list of all sheet IDs
        $allSheetIds = $this->getSheetsIdsFromSpreadsheetData($spreadsheetData);

        // Filter out the sheet IDs to keep
        $sheetIdsToDelete = array_diff($allSheetIds, $keepSheetIds);

        // Iterate over sheet IDs to create delete requests
        foreach ($sheetIdsToDelete as $sheetId) {
            // Get DeleteSheet request
            $deleteSheetRequest = [
                'deleteSheet' => Helpers::getJsonableArray(new DeleteSheetRequest(
                    sheetId: $sheetId
                ))
            ];

            // Push request to requests array
            $requests['requests'][] = $deleteSheetRequest;
        }

        // Return just the list of objects if requested
        if ($getRequestObjectsOnly) {
            return $requests['requests'];
        }

        // Exec request
        $response = $this->performRequest(
            method: "POST",
            endpoint: $spreadsheetId . ':batchUpdate',
            body: json_encode($requests),
        );

        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $sourceSpreadsheetId
     * @param int $sheetId
     * @param string|null $destinySpreadsheetId
     * @return array
     * @throws GuzzleException
     */
    public function copySheet(
        string $sourceSpreadsheetId,
        int $sheetId,
        string $destinySpreadsheetId = null,
    ): array {
        // Build request
        $request = [
            'destinationSpreadsheetId' => $destinySpreadsheetId ?: $sourceSpreadsheetId,
        ];
        // Exec request
        $response = $this->performRequest(
            method: "POST",
            endpoint: $sourceSpreadsheetId . '/sheets/' . $sheetId . ':copyTo',
            body: json_encode($request),
        );
        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    // For updating cells properties and not just values
    // Default params will clear the sheet
    /**
     * @param string $spreadsheetId
     * @param array $rows
     * @param string $fields
     * @param int|null $sheetId
     * @param int|null $sheetIndex
     * @param string|null $sheetTitle
     * @param string|int $startColumnIndex
     * @param int $startRowIndex
     * @param string|int $endColumnIndex
     * @param int $endRowIndex
     * @param array|null $spreadsheetData
     * @param bool $getRequestObjectOnly
     * @return array
     * @throws GuzzleException
     */
    public function updateCells(
        string $spreadsheetId,
        array $rows = [],
        string $fields = "*",
        int $sheetId = null,
        int $sheetIndex = null,
        string $sheetTitle = null,
        string|int $startColumnIndex = 1,
        int $startRowIndex = 1,
        string|int $endColumnIndex = 1000000,
        int $endRowIndex = 1000000,
        array $spreadsheetData = null,
        bool $getRequestObjectOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];
        // Format Rows
        $formattedRows = $this->formatRows($rows);
        // Check range
        if (!$this->buildValidRange(
            startColumnIndex: $startColumnIndex,
            startRowIndex: $startRowIndex,
            endColumnIndex: $endColumnIndex,
            endRowIndex: $endRowIndex,
        )) {
            return [
                'error' => 'Invalid range'
            ];
        }
        // Check sheet ID
        $sheetId = $this->checkSheetId(
            spreadsheetId: $spreadsheetId,
            sheetId: $sheetId,
            spreadsheetData: $spreadsheetData,
            sheetTitle: $sheetTitle,
            sheetIndex: $sheetIndex,
        );
        // Build Grid Range
        $gridRange = new GridRange(
            sheetId: $sheetId,
            startRowIndex: $startRowIndex,
            endRowIndex: $endRowIndex,
            startColumnIndex: $startColumnIndex,
            endColumnIndex: $endColumnIndex,
        );
        // Build request
        $params = [
            'rows' => $formattedRows,
            'range' => $gridRange,
        ];
        if ($fields) {
            $params['fields'] = $fields;
        }
        // Get AddSheet request
        $updateCellsRequest = [
            'updateCells' => Helpers::getJsonableArray(new UpdateCellsRequest(...$params))
        ];
        // Return just the object if requested
        if ($getRequestObjectOnly) {
            return $updateCellsRequest;
        }
        // Push requests
        $requests['requests'][] = $updateCellsRequest;
        // Helpers::printJsonObject(json_encode($requests));
        // Exec request
        $response = $this->performRequest(
            method: "POST",
            endpoint: $spreadsheetId . ':batchUpdate',
            body: json_encode($requests),
        );
        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    // Default params will clear the sheet

    /**
     * @param string $spreadsheetId
     * @param array $sheetsData Array of sheets data where each element is an associative array with the following keys:
     *                          - 'rows' (array): The rows data to update.
     *                          - 'fields' (string, optional): The fields to update. Default is '*'.
     *                          - 'sheetId' (int, optional): The ID of the sheet. Default is null.
     *                          - 'sheetIndex' (int, optional): The index of the sheet. Default is null.
     *                          - 'sheetTitle' (string, optional): The title of the sheet. Default is null.
     *                          - 'startColumnIndex' (string|int, optional): The start column index. Default is 1.
     *                          - 'startRowIndex' (int, optional): The start row index. Default is 1.
     *                          - 'endColumnIndex' (string|int, optional): The end column index. Default is 1000000.
     *                          - 'endRowIndex' (int, optional): The end row index. Default is 1000000.
     * @param array|null $spreadsheetData
     * @param bool $getRequestObjectsOnly
     * @return array
     * @throws GuzzleException
     */
    public function updateMultipleSheetsCells(
        string $spreadsheetId,
        array $sheetsData,
        array $spreadsheetData = null,
        bool $getRequestObjectsOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];

        // Get the current spreadsheet data if not provided
        if (is_null($spreadsheetData)) {
            $spreadsheetData = $this->getSpreadsheetData($spreadsheetId);
        }

        // Iterate over sheets data to create update requests
        foreach ($sheetsData as $sheetData) {
            $rows = $sheetData['rows'] ?? [];
            $fields = $sheetData['fields'] ?? '*';
            $sheetId = $sheetData['sheetId'] ?? null;
            $sheetIndex = $sheetData['sheetIndex'] ?? null;
            $sheetTitle = $sheetData['sheetTitle'] ?? null;
            $startColumnIndex = $sheetData['startColumnIndex'] ?? 1;
            $startRowIndex = $sheetData['startRowIndex'] ?? 1;
            $endColumnIndex = $sheetData['endColumnIndex'] ?? 1000000;
            $endRowIndex = $sheetData['endRowIndex'] ?? 1000000;

            // Format Rows
            $formattedRows = $this->formatRows($rows);

            // Check range
            if (!$this->buildValidRange(
                startColumnIndex: $startColumnIndex,
                startRowIndex: $startRowIndex,
                endColumnIndex: $endColumnIndex,
                endRowIndex: $endRowIndex,
            )) {
                return [
                    'error' => 'Invalid range'
                ];
            }

            // Check sheet ID
            $sheetId = $this->checkSheetId(
                spreadsheetId: $spreadsheetId,
                sheetId: $sheetId,
                spreadsheetData: $spreadsheetData,
                sheetTitle: $sheetTitle,
                sheetIndex: $sheetIndex,
            );

            // Build Grid Range
            $gridRange = new GridRange(
                sheetId: $sheetId,
                startRowIndex: $startRowIndex,
                endRowIndex: $endRowIndex,
                startColumnIndex: $startColumnIndex,
                endColumnIndex: $endColumnIndex,
            );

            // Build request
            $params = [
                'rows' => $formattedRows,
                'range' => $gridRange,
            ];
            if ($fields) {
                $params['fields'] = $fields;
            }

            // Get UpdateCells request
            $updateCellsRequest = [
                'updateCells' => Helpers::getJsonableArray(new UpdateCellsRequest(...$params))
            ];

            // Push request to requests array
            $requests['requests'][] = $updateCellsRequest;
        }

        // Return just the list of objects if requested
        if ($getRequestObjectsOnly) {
            return $requests['requests'];
        }

        // Exec request
        $response = $this->performRequest(
            method: "POST",
            endpoint: $spreadsheetId . ':batchUpdate',
            body: json_encode($requests),
        );

        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $spreadsheetId
     * @param int|null $sheetId
     * @param int|null $sheetIndex
     * @param string|null $sheetTitle
     * @param string|int $startColumnIndex
     * @param int $startRowIndex
     * @param string|int $endColumnIndex
     * @param int $endRowIndex
     * @param array|null $spreadsheetData
     * @return array
     * @throws GuzzleException
     */
    public function clearCells(
        string $spreadsheetId,
        int $sheetId = null,
        int $sheetIndex = null,
        string $sheetTitle = null,
        string|int $startColumnIndex = 'A',
        int $startRowIndex = 1,
        string|int $endColumnIndex = 'ZZ',
        int $endRowIndex = 1000000,
        array $spreadsheetData = null,
    ): array {
        // Get spreadsheet data if not provided
        if (is_null($spreadsheetData)) {
            $spreadsheetData = $this->getSpreadsheetData($spreadsheetId);
        }
        // Check sheet Title
        if (!$sheetTitle && ($sheetIndex || $sheetId)) {
            $sheetTitle = $this->getSheetTitle(
                spreadsheetData: $spreadsheetData,
                sheetId: $sheetId,
                sheetIndex: $sheetIndex,
            );
        }
        // Build range
        $range = Helpers::getRange(
            sheetTitle: $sheetTitle,
            startColumnIndex: $startColumnIndex,
            startRowIndex: $startRowIndex,
            endColumnIndex: $endColumnIndex,
            endRowIndex: $endRowIndex,
        );
        // Exec request
        $response = $this->performRequest(
            method: "POST",
            endpoint: $spreadsheetId . '/values/' . $range . ':clear',
        );
        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $spreadsheetId
     * @param int|null $sheetId
     * @param int|null $sheetIndex
     * @param string|null $sheetTitle
     * @param ClearSheetMethods $method
     * @return array
     * @throws GuzzleException
     */
    public function clearSheet(
        string $spreadsheetId,
        int $sheetId = null,
        int $sheetIndex = null,
        string $sheetTitle = null,
        ClearSheetMethods $method = ClearSheetMethods::CLEAR_CELLS,
    ): array {
        return match ($method) {
            ClearSheetMethods::CLEAR_CELLS => $this->clearCells(
                spreadsheetId: $spreadsheetId,
                sheetId: $sheetId,
                sheetIndex: $sheetIndex,
                sheetTitle: $sheetTitle,
            ),
            ClearSheetMethods::UPDATE_CELLS => $this->updateCells(
                spreadsheetId: $spreadsheetId,
                sheetId: $sheetId,
                sheetIndex: $sheetIndex,
                sheetTitle: $sheetTitle,
            ),
            ClearSheetMethods::WRITE_CELLS => $this->writeCells(
                spreadsheetId: $spreadsheetId,
                sheetId: $sheetId,
                sheetIndex: $sheetIndex,
                sheetTitle: $sheetTitle,
            ),
            default => false,
        };
    }

    /**
     * @param string $spreadsheetId
     * @param int|null $sheetId
     * @param int|null $sheetIndex
     * @param string|null $sheetTitle
     * @param string|int $startColumnIndex
     * @param int $startRowIndex
     * @param string|int $endColumnIndex
     * @param int $endRowIndex
     * @param array|null $spreadsheetData
     * @return array
     * @throws GuzzleException
     */
    public function readCells(
        string $spreadsheetId,
        int $sheetId = null,
        int $sheetIndex = null,
        string $sheetTitle = null,
        string|int $startColumnIndex = 'A',
        int $startRowIndex = 1,
        string|int $endColumnIndex = 'ZZ',
        int $endRowIndex = 1000000,
        array $spreadsheetData = null,
    ): array {
        // Get spreadsheet data if not provided
        if (is_null($spreadsheetData)) {
            $spreadsheetData = $this->getSpreadsheetData($spreadsheetId);
        }
        // Check sheet Title
        if (!$sheetTitle && ($sheetIndex || $sheetId)) {
            $sheetTitle = $this->getSheetTitle(
                spreadsheetData: $spreadsheetData,
                sheetId: $sheetId,
                sheetIndex: $sheetIndex,
            );
        }
        // Build range
        $range = Helpers::getRange(
            sheetTitle: $sheetTitle,
            startColumnIndex: $startColumnIndex,
            startRowIndex: $startRowIndex,
            endColumnIndex: $endColumnIndex,
            endRowIndex: $endRowIndex,
        );
        // Exec request
        $response = $this->performRequest(
            method: "GET",
            endpoint: $spreadsheetId . '/values/' . $range,
        );
        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    // Default params will clear the sheet
    /**
     * @param string $spreadsheetId
     * @param array $data
     * @param Dimension $majorDimension
     * @param int|null $sheetId
     * @param int|null $sheetIndex
     * @param string|null $sheetTitle
     * @param string|int $startColumnIndex
     * @param int $startRowIndex
     * @param string|int $endColumnIndex
     * @param int $endRowIndex
     * @param array|null $spreadsheetData
     * @param ValueInputOption $valueInputOption
     * @param ValueRenderOption $responseValueRenderOption
     * @param DateTimeRenderOption $responseDateTimeRenderOption
     * @return array
     * @throws GuzzleException
     */
    public function writeCells(
        string $spreadsheetId,
        array $data = [],
        Dimension $majorDimension = Dimension::ROWS,
        int $sheetId = null,
        int $sheetIndex = null,
        string $sheetTitle = null,
        string|int $startColumnIndex = 'A',
        int $startRowIndex = 1,
        string|int $endColumnIndex = 'ZZ',
        int $endRowIndex = 1000000,
        array $spreadsheetData = null,
        ValueInputOption $valueInputOption = ValueInputOption::USER_ENTERED,
        ValueRenderOption $responseValueRenderOption = ValueRenderOption::FORMATTED_VALUE,
        DateTimeRenderOption $responseDateTimeRenderOption = DateTimeRenderOption::SERIAL_NUMBER,
    ): array {
        // Get spreadsheet data if not provided
        if (is_null($spreadsheetData)) {
            $spreadsheetData = $this->getSpreadsheetData($spreadsheetId);
        }
        // Check sheet Title
        if (!$sheetTitle && ($sheetIndex || $sheetId)) {
            $sheetTitle = $this->getSheetTitle(
                spreadsheetData: $spreadsheetData,
                sheetId: $sheetId,
                sheetIndex: $sheetIndex,
            );
        }
        // Build range
        $range = Helpers::getRange(
            sheetTitle: $sheetTitle,
            startColumnIndex: $startColumnIndex,
            startRowIndex: $startRowIndex,
            endColumnIndex: $endColumnIndex,
            endRowIndex: $endRowIndex,
        );
        // Build request
        $request = Helpers::getJsonableArray(new ValueRange(
            range: $range,
            values: $data,
            majorDimension: $majorDimension
        ));
        // Exec request
        $response = $this->performRequest(
            method: "PUT",
            endpoint: $spreadsheetId . '/values/' . $range,
            query: [
                "valueInputOption" => $valueInputOption->name,
                "responseValueRenderOption" => $responseValueRenderOption->name,
                "responseDateTimeRenderOption" => $responseDateTimeRenderOption->name,
            ],
            body: json_encode($request),
        );
        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $spreadsheetId
     * @param GridProperties|array $gridProperties
     * @param ColorStyle|array $tabColorStyle
     * @param string $title
     * @param string|null $index
     * @param string $fields
     * @param int|null $sheetId
     * @param int|null $sheetIndex
     * @param string|null $sheetTitle
     * @param array|null $spreadsheetData
     * @param bool $getRequestObjectOnly
     * @return array
     * @throws GuzzleException
     */
    public function updateSheetProperties(
        string $spreadsheetId,
        GridProperties|array $gridProperties,
        ColorStyle|array $tabColorStyle = ['rgbColor' => []],
        string $title = "",
        ?string $index = null,
        string $fields = "*",
        int $sheetId = null,
        int $sheetIndex = null,
        string $sheetTitle = null,
        array $spreadsheetData = null,
        bool $getRequestObjectOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];
        // Check sheet ID
        $sheetId = $this->checkSheetId(
            spreadsheetId: $spreadsheetId,
            sheetId: $sheetId,
            spreadsheetData: $spreadsheetData,
            sheetTitle: $sheetTitle,
            sheetIndex: $sheetIndex,
        );
        // Build Grid Range
        $sheetProperties = new SheetProperties(
            title: $title,
            gridProperties: $gridProperties,
            tabColorStyle: $tabColorStyle,
            index: $index,
            sheetId: $sheetId,
        );
        // Build request
        $params = [
            'properties' => $sheetProperties,
        ];
        if ($fields) {
            $params['fields'] = $fields;
        }
        // Get AddSheet request
        $updateSheetPropertiesRequest = [
            'updateSheetProperties' => Helpers::getJsonableArray(new UpdateSheetPropertiesRequest(...$params))
        ];
        // Return just the object if requested
        if ($getRequestObjectOnly) {
            return $updateSheetPropertiesRequest;
        }
        // Push requests
        $requests['requests'][] = $updateSheetPropertiesRequest;
        // Helpers::printJsonObject(json_encode($requests));
        // Exec request
        $response = $this->performRequest(
            method: "POST",
            endpoint: $spreadsheetId . ':batchUpdate',
            body: json_encode($requests),
        );
        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $spreadsheetId
     * @param array $sheetsData Array of sheets data where each element is an associative array with the following keys:
     *                          - 'gridProperties' (GridProperties|array): The grid properties to update.
     *                          - 'tabColorStyle' (ColorStyle|array, optional): The tab color style. Default is ['rgbColor' => []].
     *                          - 'title' (string, optional): The title of the sheet. Default is "".
     *                          - 'index' (string|null, optional): The index of the sheet. Default is null.
     *                          - 'fields' (string, optional): The fields to update. Default is "*".
     *                          - 'sheetId' (int, optional): The ID of the sheet. Default is null.
     *                          - 'sheetIndex' (int, optional): The index of the sheet. Default is null.
     *                          - 'sheetTitle' (string, optional): The title of the sheet. Default is null.
     * @param array|null $spreadsheetData
     * @param bool $getRequestObjectsOnly
     * @return array
     * @throws GuzzleException
     */
    public function updateMultipleSheetsProperties(
        string $spreadsheetId,
        array $sheetsData,
        array $spreadsheetData = null,
        bool $getRequestObjectsOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];

        // Get the current spreadsheet data if not provided
        if (is_null($spreadsheetData)) {
            $spreadsheetData = $this->getSpreadsheetData($spreadsheetId);
        }

        // Iterate over sheets data to create update requests
        foreach ($sheetsData as $sheetData) {
            $gridProperties = $sheetData['gridProperties'];
            $tabColorStyle = $sheetData['tabColorStyle'] ?? ['rgbColor' => []];
            $title = $sheetData['title'] ?? "";
            $index = $sheetData['index'] ?? null;
            $fields = $sheetData['fields'] ?? "*";
            $sheetId = $sheetData['sheetId'] ?? null;
            $sheetIndex = $sheetData['sheetIndex'] ?? null;
            $sheetTitle = $sheetData['sheetTitle'] ?? null;

            // Check sheet ID
            $sheetId = $this->checkSheetId(
                spreadsheetId: $spreadsheetId,
                sheetId: $sheetId,
                spreadsheetData: $spreadsheetData,
                sheetTitle: $sheetTitle,
                sheetIndex: $sheetIndex,
            );

            // Build Grid Range
            $sheetProperties = new SheetProperties(
                title: $title,
                gridProperties: $gridProperties,
                tabColorStyle: $tabColorStyle,
                index: $index,
                sheetId: $sheetId,
            );

            // Build request
            $params = [
                'properties' => $sheetProperties,
            ];
            if ($fields) {
                $params['fields'] = $fields;
            }

            // Get UpdateSheetProperties request
            $updateSheetPropertiesRequest = [
                'updateSheetProperties' => Helpers::getJsonableArray(new UpdateSheetPropertiesRequest(...$params))
            ];

            // Push request to requests array
            $requests['requests'][] = $updateSheetPropertiesRequest;
        }

        // Return just the list of objects if requested
        if ($getRequestObjectsOnly) {
            return $requests['requests'];
        }

        // Exec request
        $response = $this->performRequest(
            method: "POST",
            endpoint: $spreadsheetId . ':batchUpdate',
            body: json_encode($requests),
        );

        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $spreadsheetId
     * @param Dimension $dimension
     * @param int|null $startIndex
     * @param int|null $endIndex
     * @param string $fields
     * @param int|null $sheetId
     * @param int|null $sheetIndex
     * @param string|null $sheetTitle
     * @param array|null $spreadsheetData
     * @param bool $getRequestObjectOnly
     * @return array
     * @throws GuzzleException
     */
    public function deleteDimension(
        string $spreadsheetId,
        Dimension $dimension = Dimension::ROWS,
        ?int $startIndex = null,
        ?int $endIndex = null,
        string $fields = "*",
        int $sheetId = null,
        int $sheetIndex = null,
        string $sheetTitle = null,
        array $spreadsheetData = null,
        bool $getRequestObjectOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];
        // Check sheet ID
        $sheetId = $this->checkSheetId(
            spreadsheetId: $spreadsheetId,
            sheetId: $sheetId,
            spreadsheetData: $spreadsheetData,
            sheetTitle: $sheetTitle,
            sheetIndex: $sheetIndex,
        );
        // Build Grid Range
        if ($dimension === Dimension::DIMENSION_UNSPECIFIED) {
            $dimension = Dimension::ROWS;
        }
        // Build request
        $params = [
            'range' => new DimensionRange(
                sheetId: $sheetId,
                startIndex: $startIndex ?: 0,
                endIndex: $endIndex ?: 1000,
                dimension: $dimension,
            ),
        ];
        if ($fields) {
            $params['fields'] = $fields;
        }
        // Get AddSheet request
        $deleteDimensionRequest = [
            'deleteDimension' => Helpers::getJsonableArray(new DeleteDimensionRequest(...$params))
        ];
        // Return just the object if requested
        if ($getRequestObjectOnly) {
            return $deleteDimensionRequest;
        }
        // Push requests
        $requests['requests'][] = $deleteDimensionRequest;
        // Helpers::printJsonObject(json_encode($requests));
        // Exec request
        $response = $this->performRequest(
            method: "POST",
            endpoint: $spreadsheetId . ':batchUpdate',
            body: json_encode($requests),
        );
        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $spreadsheetId
     * @param array $dimensionsData Array of dimensions data where each element is an associative array with the following keys:
     *                              - 'dimension' (Dimension, optional): The dimension (ROWS or COLUMNS). Default is Dimension::ROWS.
     *                              - 'startIndex' (int, optional): The start index. Default is null.
     *                              - 'endIndex' (int, optional): The end index. Default is null.
     *                              - 'sheetId' (int, optional): The ID of the sheet. Default is null.
     *                              - 'sheetIndex' (int, optional): The index of the sheet. Default is null.
     *                              - 'sheetTitle' (string, optional): The title of the sheet. Default is null.
     * @param array|null $spreadsheetData
     * @param bool $getRequestObjectsOnly
     * @return array
     * @throws GuzzleException
     */
    public function deleteMultipleDimensions(
        string $spreadsheetId,
        array $dimensionsData,
        array $spreadsheetData = null,
        bool $getRequestObjectsOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];

        // Get the current spreadsheet data if not provided
        if (is_null($spreadsheetData)) {
            $spreadsheetData = $this->getSpreadsheetData($spreadsheetId);
        }

        // Iterate over dimensions data to create delete requests
        foreach ($dimensionsData as $dimensionData) {
            $dimension = $dimensionData['dimension'] ?? Dimension::ROWS;
            $startIndex = $dimensionData['startIndex'] ?? null;
            $endIndex = $dimensionData['endIndex'] ?? null;
            $sheetId = $dimensionData['sheetId'] ?? null;
            $sheetIndex = $dimensionData['sheetIndex'] ?? null;
            $sheetTitle = $dimensionData['sheetTitle'] ?? null;

            // Check sheet ID
            $sheetId = $this->checkSheetId(
                spreadsheetId: $spreadsheetId,
                sheetId: $sheetId,
                spreadsheetData: $spreadsheetData,
                sheetTitle: $sheetTitle,
                sheetIndex: $sheetIndex,
            );

            // Build request
            $params = [
                'range' => new DimensionRange(
                    sheetId: $sheetId,
                    startIndex: $startIndex,
                    endIndex: $endIndex,
                    dimension: $dimension,
                )
            ];

            // Get DeleteDimension request
            $deleteDimensionRequest = [
                'deleteDimension' => Helpers::getJsonableArray(new DeleteDimensionRequest(...$params))
            ];

            // Push request to requests array
            $requests['requests'][] = $deleteDimensionRequest;
        }

        // Return just the list of objects if requested
        if ($getRequestObjectsOnly) {
            return $requests['requests'];
        }

        // Exec request
        $response = $this->performRequest(
            method: "POST",
            endpoint: $spreadsheetId . ':batchUpdate',
            body: json_encode($requests),
        );

        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $spreadsheetId
     * @param int|null $pixelSize
     * @param Dimension $dimension
     * @param int|null $startIndex
     * @param int|null $endIndex
     * @param string $fields
     * @param int|null $sheetId
     * @param int|null $sheetIndex
     * @param string|null $sheetTitle
     * @param array|null $spreadsheetData
     * @param bool $getRequestObjectOnly
     * @return array
     * @throws GuzzleException
     */
    public function updateDimensionProperties(
        string $spreadsheetId,
        ?int $pixelSize = null, // 21 for rows height and 100 for columns width
        Dimension $dimension = Dimension::ROWS,
        ?int $startIndex = null,
        ?int $endIndex = null,
        string $fields = "*",
        int $sheetId = null,
        int $sheetIndex = null,
        string $sheetTitle = null,
        array $spreadsheetData = null,
        bool $getRequestObjectOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];
        // Check sheet ID
        $sheetId = $this->checkSheetId(
            spreadsheetId: $spreadsheetId,
            sheetId: $sheetId,
            spreadsheetData: $spreadsheetData,
            sheetTitle: $sheetTitle,
            sheetIndex: $sheetIndex,
        );
        // Build Grid Range
        if ($dimension === Dimension::DIMENSION_UNSPECIFIED) {
            $dimension = Dimension::ROWS;
        }
        $dimensionProperties = new DimensionProperties(
            pixelSize: $pixelSize ?: ($dimension === Dimension::ROWS ? 21 : 100),
        );
        // Build request
        $params = [
            'properties' => $dimensionProperties,
            'range' => new DimensionRange(
                sheetId: $sheetId,
                startIndex: $startIndex ?: 0,
                endIndex: $endIndex ?: 1000,
                dimension: $dimension,
            ),
        ];
        if ($fields) {
            $params['fields'] = $fields;
        }
        // Get AddSheet request
        $updateDimensionPropertiesRequest = [
            'updateDimensionProperties' => Helpers::getJsonableArray(new UpdateDimensionPropertiesRequest(...$params))
        ];
        // Return just the object if requested
        if ($getRequestObjectOnly) {
            return $updateDimensionPropertiesRequest;
        }
        // Push requests
        $requests['requests'][] = $updateDimensionPropertiesRequest;
        // Helpers::printJsonObject(json_encode($requests));
        // Exec request
        $response = $this->performRequest(
            method: "POST",
            endpoint: $spreadsheetId . ':batchUpdate',
            body: json_encode($requests),
        );
        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $spreadsheetId
     * @param array $dimensionsData Array of dimensions data where each element is an associative array with the following keys:
     *                              - 'pixelSize' (int, optional): The pixel size. Default is null.
     *                              - 'dimension' (Dimension, optional): The dimension (ROWS or COLUMNS). Default is Dimension::ROWS.
     *                              - 'startIndex' (int, optional): The start index. Default is null.
     *                              - 'endIndex' (int, optional): The end index. Default is null.
     *                              - 'fields' (string, optional): The fields to update. Default is "*".
     *                              - 'sheetId' (int, optional): The ID of the sheet. Default is null.
     *                              - 'sheetIndex' (int, optional): The index of the sheet. Default is null.
     *                              - 'sheetTitle' (string, optional): The title of the sheet. Default is null.
     * @param array|null $spreadsheetData
     * @param bool $getRequestObjectsOnly
     * @return array
     * @throws GuzzleException
     */
    public function updateMultipleDimensionsProperties(
        string $spreadsheetId,
        array $dimensionsData,
        array $spreadsheetData = null,
        bool $getRequestObjectsOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];

        // Get the current spreadsheet data if not provided
        if (is_null($spreadsheetData)) {
            $spreadsheetData = $this->getSpreadsheetData($spreadsheetId);
        }

        // Iterate over dimensions data to create update requests
        foreach ($dimensionsData as $dimensionData) {
            $pixelSize = $dimensionData['pixelSize'] ?? null;
            $dimension = $dimensionData['dimension'] ?? Dimension::ROWS;
            $startIndex = $dimensionData['startIndex'] ?? null;
            $endIndex = $dimensionData['endIndex'] ?? null;
            $fields = $dimensionData['fields'] ?? "*";
            $sheetId = $dimensionData['sheetId'] ?? null;
            $sheetIndex = $dimensionData['sheetIndex'] ?? null;
            $sheetTitle = $dimensionData['sheetTitle'] ?? null;

            // Check sheet ID
            $sheetId = $this->checkSheetId(
                spreadsheetId: $spreadsheetId,
                sheetId: $sheetId,
                spreadsheetData: $spreadsheetData,
                sheetTitle: $sheetTitle,
                sheetIndex: $sheetIndex,
            );

            // Build Dimension Properties
            $dimensionProperties = new DimensionProperties(
                pixelSize: $pixelSize ?: ($dimension === Dimension::ROWS ? 21 : 100),
            );

            // Build request
            $params = [
                'properties' => $dimensionProperties,
                'range' => new DimensionRange(
                    sheetId: $sheetId,
                    startIndex: $startIndex,
                    endIndex: $endIndex,
                    dimension: $dimension,
                )
            ];
            if ($fields) {
                $params['fields'] = $fields;
            }

            // Get UpdateDimensionProperties request
            $updateDimensionPropertiesRequest = [
                'updateDimensionProperties' => Helpers::getJsonableArray(new UpdateDimensionPropertiesRequest(...$params))
            ];

            // Push request to requests array
            $requests['requests'][] = $updateDimensionPropertiesRequest;
        }

        // Return just the list of objects if requested
        if ($getRequestObjectsOnly) {
            return $requests['requests'];
        }

        // Exec request
        $response = $this->performRequest(
            method: "POST",
            endpoint: $spreadsheetId . ':batchUpdate',
            body: json_encode($requests),
        );

        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $spreadsheetId
     * @param array $rows
     * @param string $fields
     * @param int|null $sheetId
     * @param int|null $sheetIndex
     * @param string|null $sheetTitle
     * @param int $rowIndex
     * @param int $columnIndex
     * @param array|null $spreadsheetData
     * @param bool $getRequestObjectOnly
     * @return array
     * @throws GuzzleException
     */
    public function addPivotTable(
        string $spreadsheetId,
        array $rows = [],
        string $fields = "*",
        int $sheetId = null,
        int $sheetIndex = null,
        string $sheetTitle = null,
        int $rowIndex = 1,
        int $columnIndex = 1,
        array $spreadsheetData = null,
        bool $getRequestObjectOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];
        // Format Rows
        $formattedRows = $this->formatRows($rows);
        // Check sheet ID
        $sheetId = $this->checkSheetId(
            spreadsheetId: $spreadsheetId,
            sheetId: $sheetId,
            spreadsheetData: $spreadsheetData,
            sheetTitle: $sheetTitle,
            sheetIndex: $sheetIndex,
        );
        // Build Grid Range
        $gridCoordinate = new GridCoordinate(
            sheetId: $sheetId,
            rowIndex: $rowIndex,
            columnIndex: $columnIndex,
        );
        // Build request
        $params = [
            'rows' => $formattedRows,
            'start' => $gridCoordinate,
        ];
        if ($fields) {
            $params['fields'] = $fields;
        }
        // Get AddSheet request
        $updateCellsRequest = [
            'updateCells' => Helpers::getJsonableArray(new UpdateCellsRequest(...$params))
        ];
        // Return just the object if requested
        if ($getRequestObjectOnly) {
            return $updateCellsRequest;
        }
        // Push requests
        $requests['requests'][] = $updateCellsRequest;
        // Helpers::printJsonObject(json_encode($requests));
        // Exec request
        $response = $this->performRequest(
            method: "POST",
            endpoint: $spreadsheetId . ':batchUpdate',
            body: json_encode($requests),
        );
        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $spreadsheetId
     * @param int $chartId
     * @param array $chartData
     * @param string|null $dataSourceId
     * @param array|null $filterSpecs
     * @param array|null $sortSpecs
     * @param string $title
     * @param string $subtitle
     * @param string $fontName
     * @param int|null $sheetId
     * @param int|null $sheetIndex
     * @param string|null $sheetTitle
     * @param array|null $spreadsheetData
     * @param ChartTypes $chartType
     * @param bool $getRequestObjectOnly
     * @return array
     * @throws GuzzleException
     */
    public function addChart(
        string $spreadsheetId,
        int $chartId,
        array $chartData,
        string $dataSourceId = null,
        array $filterSpecs = null,
        array $sortSpecs = null,
        string $title = 'New Chart',
        string $subtitle = '',
        string $fontName = 'Roboto',
        int $sheetId = null,
        int $sheetIndex = null,
        string $sheetTitle = null,
        array $spreadsheetData = null,
        ChartTypes $chartType = ChartTypes::BASIC,
        bool $getRequestObjectOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];
        // Get spreadsheet data if not provided
        if (is_null($spreadsheetData)) {
            $spreadsheetData = $this->getSpreadsheetData($spreadsheetId);
        }
        if (isset($spreadsheetData["sheets"])) {
            if ($sheetId || $sheetIndex || $sheetTitle) {
                // Check sheet ID
                $sheetId = $this->checkSheetId(
                    spreadsheetId: $spreadsheetId,
                    sheetId: $sheetId,
                    spreadsheetData: $spreadsheetData,
                    sheetTitle: $sheetTitle,
                    sheetIndex: $sheetIndex,
                );
                $embeddedObjectPosition = [
                    'sheetId' => $sheetId,
                ];
            } else {
                $embeddedObjectPosition = [
                    'newSheet' => true,
                ];
            }
            // Format Filter Specs
            $formattedFilterSpecs = [];
            if ($sortSpecs) {
                foreach ($filterSpecs as $filterSpec) {
                    if (!($filterSpec instanceof FilterSpec)) {
                        $formattedFilterSpecs[] = new FilterSpec(...$filterSpec);
                    } else {
                        $formattedFilterSpecs[] = $filterSpec;
                    }
                }
            }
            // Format Sort Specs
            // Only a single sort spec is supported
            $formattedSortSpecs = [];
            if ($sortSpecs) {
                if (!($sortSpecs[0] instanceof SortSpec)) {
                    $formattedSortSpecs[] = new SortSpec(...$sortSpecs[0]);
                } else {
                    $formattedSortSpecs[] = $sortSpecs[0];
                }
            }
            // Set Chart Spec
            $chartSpec = new ChartSpec(
                title: $title,
                subtitle: $subtitle,
                subtitleTextFormat: new TextFormat(
                    foregroundColorStyle: new ColorStyle(),
                ),
                fontName: $fontName,
                titleTextFormat: new TextFormat(
                    foregroundColorStyle: new ColorStyle(),
                    strikethrough: null, // Not supported for title
                    underline: null, // Not supported for title
                ),
                dataSourceChartProperties: ( // Not supported for BASIC charts
                $chartType == ChartTypes::BASIC ?
                    null :
                    new DataSourceChartProperties(
                        dataSourceId: $dataSourceId,
                    )
                ),
                filterSpecs: $formattedFilterSpecs,
                sortSpecs: $formattedSortSpecs,
            );
            // Set Chart Data
            $chartSpec->setChartData($chartType, $chartData);
            // Embed Chart
            $chart = new EmbeddedChart(
                chartId: $chartId,
                spec: $chartSpec,
                position: new EmbeddedObjectPosition(...$embeddedObjectPosition),
                border: new EmbeddedObjectBorder(
                    colorStyle: new ColorStyle(),
                ),
            );
            // Get AddSheet request
            $addSheetRequest = [
                'addChart' => Helpers::getJsonableArray(new AddChartRequest(
                    chart: $chart
                ))
            ];
            // Return just the object if requested
            if ($getRequestObjectOnly) {
                return $addSheetRequest;
            }
            // Push requests
            $requests['requests'][] = $addSheetRequest;
            // Helpers::printJsonObject(json_encode($requests));
            // Exec request
            $response = $this->performRequest(
                method: "POST",
                endpoint: $spreadsheetId . ':batchUpdate',
                body: json_encode($requests),
            );
            // Return response
            return json_decode($response->getBody()->getContents(), true);
        }
        // If there are no sheets, return error
        return [
            'error' => 'Error getting spreadsheet\'s sheets'
        ];
    }

    /**
     * @param string $spreadsheetId
     * @param GridRange|array $sourceRange
     * @param GridRange|array $destinationRange
     * @param PasteType|string $pasteType
     * @param bool $transpose
     * @param array|null $spreadsheetData
     * @param bool $getRequestObjectOnly
     * @return array
     * @throws GuzzleException
     */
    public function copyAndPaste(
        string $spreadsheetId,
        GridRange|array $sourceRange,
        GridRange|array $destinationRange,
        PasteType|string $pasteType = PasteType::PASTE_NORMAL,
        bool $transpose = false,
        array $spreadsheetData = null,
        bool $getRequestObjectOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];
        // Get spreadsheet data if not provided
        if (is_null($spreadsheetData)) {
            $spreadsheetData = $this->getSpreadsheetData($spreadsheetId);
        }
        if (isset($spreadsheetData["sheets"])) {
            // Get DeleteSheet request
            $copyPasteRequest = [
                'copyPaste' => Helpers::getJsonableArray(new CopyPasteRequest(
                    source: $sourceRange,
                    destination: $destinationRange,
                    pasteType: $pasteType,
                    pasteOrientation: $transpose ? PasteOrientation::TRANSPOSE : PasteOrientation::NORMAL,
                ))
            ];
            // Return just the object if requested
            if ($getRequestObjectOnly) {
                return $copyPasteRequest;
            }
            // Push requests
            $requests['requests'][] = $copyPasteRequest;
            // Exec request
            $response = $this->performRequest(
                method: "POST",
                endpoint: $spreadsheetId . ':batchUpdate',
                body: json_encode($requests),
            );
            // Return response
            return json_decode($response->getBody()->getContents(), true);
        }
        // If there are no sheets, return error
        return [
            'error' => 'Error getting spreadsheet\'s sheets'
        ];
    }

    /**
     * @param array $spreadsheetData
     * @return array
     */
    public function getSheetsIdsFromSpreadsheetData(
        array $spreadsheetData
    ): array {
        return array_map(function ($sheet) {
            return $sheet["properties"]["sheetId"];
        }, $spreadsheetData["sheets"]);
    }

    /**
     * @param array $spreadsheetData
     * @return array
     */
    public function getSheetsTitlesFromSpreadsheetData(
        array $spreadsheetData
    ): array {
        return array_map(function ($sheet) {
            return $sheet["properties"]["title"];
        }, $spreadsheetData["sheets"]);
    }

    /**
     * @param array $spreadsheetData
     * @param int|null $sheetId
     * @param int|null $sheetIndex
     * @return string
     */
    public function getSheetTitle(
        array $spreadsheetData,
        int $sheetId = null,
        int $sheetIndex = null
    ): string {
        if ($sheetId) {
            foreach ($spreadsheetData["sheets"] as $sheet) {
                if ($sheet["properties"]["sheetId"] == $sheetId) {
                    return $sheet["properties"]["title"];
                }
            }
        } elseif (!$sheetIndex) {
            $sheetIndex = 0;
        }
        return $spreadsheetData["sheets"][$sheetIndex]["properties"]["title"];
    }

    /**
     * @param array $spreadsheetData
     * @param string|null $sheetTitle
     * @param int|null $sheetIndex
     * @return string
     */
    public function getSheetId(
        array $spreadsheetData,
        string $sheetTitle = null,
        int $sheetIndex = null
    ): string {
        if ($sheetTitle) {
            foreach ($spreadsheetData["sheets"] as $sheet) {
                if ($sheet["properties"]["title"] == $sheetTitle) {
                    return $sheet["properties"]["sheetId"];
                }
            }
        } elseif (!$sheetIndex) {
            $sheetIndex = 0;
        }
        return $spreadsheetData["sheets"][$sheetIndex]["properties"]["sheetId"];
    }

    /**
     * @param array $spreadsheetData
     * @param string|null $sheetTitle
     * @param int|null $sheetId
     * @return string
     */
    public function getSheetIndex(
        array $spreadsheetData,
        string $sheetTitle = null,
        int $sheetId = null
    ): string {
        if ($sheetTitle) {
            foreach ($spreadsheetData["sheets"] as $key => $sheet) {
                if ($sheet["properties"]["title"] == $sheetTitle) {
                    return $key;
                }
            }
        } elseif ($sheetId) {
            foreach ($spreadsheetData["sheets"] as $key => $sheet) {
                if ($sheet["properties"]["sheetId"] == $sheetId) {
                    return $key;
                }
            }
        }
        return 0;
    }

    /**
     * @param string $spreadsheetId
     * @param int|null $sheetId
     * @param int|null $sheetIndex
     * @param string|null $sheetTitle
     * @param array|null $spreadsheetData
     * @return array
     * @throws GuzzleException
     */
    public function getSheetDimensions(
        string $spreadsheetId,
        int $sheetId = null,
        int $sheetIndex = null,
        string $sheetTitle = null,
        array $spreadsheetData = null,
    ): array {
        // Get the current spreadsheet data if not provided
        if (is_null($spreadsheetData)) {
            $spreadsheetData = $this->getSpreadsheetData($spreadsheetId);
        }

        // Check sheet ID
        $sheetId = $this->checkSheetId(
            spreadsheetId: $spreadsheetId,
            sheetId: $sheetId,
            spreadsheetData: $spreadsheetData,
            sheetTitle: $sheetTitle,
            sheetIndex: $sheetIndex,
        );

        // Find the sheet data
        foreach ($spreadsheetData['sheets'] as $sheet) {
            if ($sheet['properties']['sheetId'] == $sheetId) {
                $gridProperties = $sheet['properties']['gridProperties'];
                return [
                    'rowCount' => $gridProperties['rowCount'],
                    'columnCount' => $gridProperties['columnCount'],
                ];
            }
        }

        // If sheet not found, return error
        return [
            'error' => 'Sheet not found'
        ];
    }

    /**
     * @param string $spreadsheetId
     * @param array|null $spreadsheetData
     * @return array
     * @throws GuzzleException
     */
    public function getAllSheetsDimensions(
        string $spreadsheetId,
        array $spreadsheetData = null,
    ): array {
        // Get the current spreadsheet data if not provided
        if (is_null($spreadsheetData)) {
            $spreadsheetData = $this->getSpreadsheetData($spreadsheetId);
        }

        // Initialize dimensions array
        $dimensions = [];

        // Iterate over each sheet to get its dimensions
        foreach ($spreadsheetData['sheets'] as $sheet) {
            $sheetId = $sheet['properties']['sheetId'];
            $title = $sheet['properties']['title'];
            $gridProperties = $sheet['properties']['gridProperties'];
            $dimensions[] = [
                'sheetId' => $sheetId,
                'title' => $title,
                'rowCount' => $gridProperties['rowCount'],
                'columnCount' => $gridProperties['columnCount'],
            ];
        }

        return $dimensions;
    }

    /**
     * @param string $spreadsheetId
     * @param int|null $sheetId
     * @param array|null $spreadsheetData
     * @param string|null $sheetTitle
     * @param int|null $sheetIndex
     * @return int
     * @throws GuzzleException
     */
    public function checkSheetId(
        string $spreadsheetId,
        int $sheetId = null,
        array $spreadsheetData = null,
        string $sheetTitle = null,
        int $sheetIndex = null
    ): int {
        if (!$sheetId) {
            // Get spreadsheet data if not provided
            if (is_null($spreadsheetData)) {
                $spreadsheetData = $this->getSpreadsheetData($spreadsheetId);
            }
            if (isset($spreadsheetData["sheets"])) {
                $sheetId = $this->getSheetId(
                    spreadsheetData: $spreadsheetData,
                    sheetTitle: $sheetTitle,
                    sheetIndex: $sheetIndex,
                );
            }
        }
        return $sheetId;
    }

    /**
     * @param string|int $startColumnIndex
     * @param int $startRowIndex
     * @param string|int $endColumnIndex
     * @param int $endRowIndex
     * @return bool
     */
    protected function buildValidRange(
        string|int &$startColumnIndex = 'A',
        int &$startRowIndex = 1,
        string|int $endColumnIndex = 'Z',
        int $endRowIndex = 1000000
    ): bool {
        if (!is_int($startColumnIndex)) {
            $startColumnIndex = Helpers::getColumnIndex($startColumnIndex);
        }
        if (!is_int($endColumnIndex)) {
            $endColumnIndex = Helpers::getColumnIndex($endColumnIndex);
        }
        if (($startColumnIndex > $endColumnIndex) || ($startRowIndex > $endRowIndex) || ($endColumnIndex < 1) || ($endRowIndex < 1)) {
            return false;
        }
        if ($startColumnIndex < 1) {
            $startColumnIndex = 1;
        }
        if ($startRowIndex < 1) {
            $startRowIndex = 1;
        }
        $startColumnIndex--;
        $startRowIndex--;
        return true;
    }

    /**
     * @param array $rows
     * @return array
     */
    protected function formatRows(
        array $rows
    ): array {
        $formattedRows = [];
        foreach ($rows as $row) {
            if (!($row instanceof RowData)) {
                $formattedRows[] = new RowData(...$row);
            } else {
                $formattedRows[] = $row;
            }
        }
        return $formattedRows;
    }
}
