<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides;

use Anibalealvarezs\GoogleApi\Google\GoogleApi;
use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Dimension;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Other\Placeholder;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Page;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Tables\TableCellLocation;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Tables\TableCellProperties;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Tables\TableColumnProperties;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Tables\TableRowProperties;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Text\TextStyle;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Presentations\Request\LayoutReference;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Presentations\Request\PageElementProperties;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Presentations\Request\Range;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Presentations\Request\TableRange;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Size;
use Anibalealvarezs\GoogleApi\Services\Slides\Enums\Presentations\Request\LinkingMode;
use Anibalealvarezs\GoogleApi\Services\Slides\Requests\CreateImageRequest;
use Anibalealvarezs\GoogleApi\Services\Slides\Requests\CreatePresentationRequest;
use Anibalealvarezs\GoogleApi\Services\Slides\Requests\CreateSheetsChartRequest;
use Anibalealvarezs\GoogleApi\Services\Slides\Requests\CreateSlideRequest;
use Anibalealvarezs\GoogleApi\Services\Slides\Requests\CreateTableRequest;
use Anibalealvarezs\GoogleApi\Services\Slides\Requests\DeleteTextRequest;
use Anibalealvarezs\GoogleApi\Services\Slides\Requests\InsertTextRequest;
use Anibalealvarezs\GoogleApi\Services\Slides\Requests\UpdateTableCellPropertiesRequest;
use Anibalealvarezs\GoogleApi\Services\Slides\Requests\UpdateTableColumnPropertiesRequest;
use Anibalealvarezs\GoogleApi\Services\Slides\Requests\UpdateTableRowPropertiesRequest;
use Anibalealvarezs\GoogleApi\Services\Slides\Requests\UpdateTextStyleRequest;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;

class SlidesApi extends GoogleApi
{
    /**
     * @param string $redirectUrl
     * @param string $clientId
     * @param string $clientSecret
     * @param string $refreshToken
     * @param string $userId
     * @param array $scopes
     * @param string $token
     * @throws GuzzleException
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
            baseUrl: "https://slides.googleapis.com/v1/presentations/",
            redirectUrl: $redirectUrl,
            clientId: $clientId,
            clientSecret: $clientSecret,
            refreshToken: $refreshToken,
            userId: $userId,
            scopes: ($scopes ?: ["https://www.googleapis.com/auth/presentations", "https://www.googleapis.com/auth/drive", "https://www.googleapis.com/auth/spreadsheets"]),
            token: $token
        );
    }

    /**
     * @param string $presentationId
     * @return array
     * @throws GuzzleException
     */
    public function getPresentationData(
        string $presentationId
    ): array {
        // Request the presentation data
        $response = $this->performRequest(
            method: "GET",
            endpoint: $presentationId,
        );
        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $presentationId
     * @return array
     * @throws GuzzleException
     */
    public function getLayoutsData(
        string $presentationId
    ): array {
        $presentationData = $this->getPresentationData($presentationId);
        // Return layouts data
        return $presentationData['layouts'];
    }

    /**
     * @param string $presentationId
     * @return array
     * @throws GuzzleException
     */
    public function getLayouts(
        string $presentationId
    ): array {
        $layouts = [];
        $layoutsData = $this->getLayoutsData($presentationId);
        foreach ($layoutsData as $layoutData) {
            $layouts[] = new Page(...$layoutData);
        }
        // Return layouts
        return $layouts;
    }

    /**
     * @param string|null $presentationId
     * @param Size|array|null $pageSize
     * @param array|null $slides
     * @param string|null $title
     * @param array|null $masters
     * @param array|null $layouts
     * @param string|null $locale
     * @param string|null $revisionId
     * @param Page|null $notesMaster
     * @return array
     * @throws GuzzleException
     */
    public function createPresentation(
        ?string $presentationId = null,
        Size|array|null $pageSize = null,
        ?array $slides = null,
        ?string $title = null,
        ?array $masters = null,
        ?array $layouts = null,
        ?string $locale = null,
        ?string $revisionId = null,
        ?Page $notesMaster = null
    ): array {
        // Create parameters list
        $params = [];
        // Push presentationId to params list
        if ($presentationId) {
            $params['presentationId'] = $presentationId;
        }
        // Push pageSize to params list
        if ($pageSize) {
            if (is_array($pageSize) && isset($pageSize['width']) && isset($pageSize['height'])) {
                $pageSize = new Size(
                    width: new Dimension($pageSize['width']),
                    height: new Dimension($pageSize['height'])
                );
            }
            if (!($pageSize instanceof Size)) {
                throw new InvalidArgumentException("Invalid pageSize");
            }
            $params['pageSize'] = $pageSize;
        }
        // Push slides to params list
        if ($slides) {
            $params['slides'] = $slides;
        }
        // Push title to params list
        if ($title) {
            $params['title'] = $title;
        }
        // Push masters to params list
        if ($masters) {
            $params['masters'] = $masters;
        }
        // Push layouts to params list
        if ($layouts) {
            $params['layouts'] = $layouts;
        }
        // Push locale to params list
        if ($locale) {
            $params['locale'] = $locale;
        }
        // Push revisionId to params list
        if ($revisionId) {
            $params['revisionId'] = $revisionId;
        }
        // Push notesMaster to params list
        if ($notesMaster) {
            $params['notesMaster'] = $notesMaster;
        }
        // Get CreatePresentation request
        $request = Helpers::getJsonableArray(new CreatePresentationRequest(...$params));
        // Exec request
        $response = $this->performRequest(
            method: "POST",
            endpoint: '',
            body: json_encode($request),
        );
        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string|null $presentationId
     * @param string $objectId
     * @param int|null $insertionIndex
     * @param LayoutReference|null $slideLayoutReference
     * @param Placeholder|null $placeholderIdMappings
     * @param array|null $presentationData
     * @param bool $checkPresentation
     * @param bool $getRequestObjectOnly
     * @return array
     * @throws GuzzleException
     */
    public function createSlide(
        string $presentationId = null,
        string $objectId = "",
        int $insertionIndex = null,
        LayoutReference $slideLayoutReference = null,
        Placeholder $placeholderIdMappings = null,
        array $presentationData = null,
        bool $checkPresentation = false,
        bool $getRequestObjectOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];
        // Get spreadsheet data if not provided
        if (is_null($presentationData) && $checkPresentation) {
            $presentationData = $this->getPresentationData($presentationId);
        }
        if (isset($presentationData["presentationId"]) || !$checkPresentation) {
            // Get CreateSlide request
            $createSlideRequest = [
                'createSlide' => Helpers::getJsonableArray(new CreateSlideRequest(
                    objectId: $objectId,
                    index: $insertionIndex,
                    slideLayoutReference: $slideLayoutReference,
                    placeholderIdMappings: $placeholderIdMappings,
                ))
            ];
            // Return just the object if requested
            if ($getRequestObjectOnly) {
                return $createSlideRequest;
            }
            // Push requests
            $requests['requests'][] = $createSlideRequest;
            // Exec request
            $response = $this->performRequest(
                method: "POST",
                endpoint: isset($presentationData["presentationId"]) && $presentationData["presentationId"] ? $presentationData["presentationId"] : $presentationId . ':batchUpdate',
                body: json_encode($requests),
            );
            // Return response
            return json_decode($response->getBody()->getContents(), true);
        }
        // If there are no sheets, return error
        return [
            'error' => 'Error getting presentation data'
        ];
    }

    /**
     * @param string|null $presentationId
     * @param string $pageId
     * @param array|null $presentationData
     * @param bool $checkPresentation
     * @return array
     * @throws GuzzleException
     */
    public function getSlide(
        string $pageId,
        string $presentationId = null,
        array $presentationData = null,
        bool $checkPresentation = false,
    ): array {
        // Get spreadsheet data if not provided
        if (is_null($presentationData) && $checkPresentation) {
            $presentationData = $this->getPresentationData($presentationId);
        }
        if (isset($presentationData["presentationId"]) || !$checkPresentation) {
            // Request the presentation data
            $response = $this->performRequest(
                method: "GET",
                endpoint: isset($presentationData["presentationId"]) && $presentationData["presentationId"] ? $presentationData["presentationId"] : $presentationId . '/pages/' . $pageId,
            );
            // Return response
            return json_decode($response->getBody()->getContents(), true);
        }
        // If there are no sheets, return error
        return [
            'error' => 'Error getting presentation data'
        ];
    }

    /**
     * @param string|null $presentationId
     * @param string $objectId
     * @param string $text
     * @param int|null $insertionIndex
     * @param TableCellLocation|array|null $cellLocation
     * @param array|null $presentationData
     * @param bool $checkPresentation
     * @param bool $getRequestObjectOnly
     * @return array
     * @throws GuzzleException
     */
    public function insertText(
        string $objectId,
        string $presentationId = null,
        string $text = "",
        int $insertionIndex = null,
        TableCellLocation|array|null $cellLocation = null,
        array $presentationData = null,
        bool $checkPresentation = false,
        bool $getRequestObjectOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];
        // Get spreadsheet data if not provided
        if (is_null($presentationData) && $checkPresentation) {
            $presentationData = $this->getPresentationData($presentationId);
        }
        if (isset($presentationData["presentationId"]) || !$checkPresentation) {
            // Get CreateSlide request
            $insertTextRequest = [
                'insertText' => Helpers::getJsonableArray(new InsertTextRequest(
                    objectId: $objectId,
                    text: $text,
                    insertionIndex: $insertionIndex,
                    cellLocation: $cellLocation,
                ))
            ];
            // Return just the object if requested
            if ($getRequestObjectOnly) {
                return $insertTextRequest;
            }
            // Push requests
            $requests['requests'][] = $insertTextRequest;
            // Exec request
            $response = $this->performRequest(
                method: "POST",
                endpoint: isset($presentationData["presentationId"]) && $presentationData["presentationId"] ? $presentationData["presentationId"] : $presentationId . ':batchUpdate',
                body: json_encode($requests),
            );
            // Return response
            return json_decode($response->getBody()->getContents(), true);
        }
        // If there are no sheets, return error
        return [
            'error' => 'Error getting presentation data'
        ];
    }

    /**
     * @param string|null $presentationId
     * @param string $objectId
     * @param Range|array|null $range
     * @param TableCellLocation|array|null $cellLocation
     * @param array|null $presentationData
     * @param bool $checkPresentation
     * @param bool $getRequestObjectOnly
     * @return array
     * @throws GuzzleException
     */
    public function deleteText(
        string $objectId,
        string $presentationId = null,
        Range|array|null $range = null,
        TableCellLocation|array|null $cellLocation = null,
        array $presentationData = null,
        bool $checkPresentation = false,
        bool $getRequestObjectOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];
        // Get spreadsheet data if not provided
        if (is_null($presentationData) && $checkPresentation) {
            $presentationData = $this->getPresentationData($presentationId);
        }
        if (isset($presentationData["presentationId"]) || !$checkPresentation) {
            // Get CreateSlide request
            $deleteTextRequest = [
                'insertText' => Helpers::getJsonableArray(new DeleteTextRequest(
                    objectId: $objectId,
                    textRange: $range,
                    cellLocation: $cellLocation,
                ))
            ];
            // Return just the object if requested
            if ($getRequestObjectOnly) {
                return $deleteTextRequest;
            }
            // Push requests
            $requests['requests'][] = $deleteTextRequest;
            // Exec request
            $response = $this->performRequest(
                method: "POST",
                endpoint: isset($presentationData["presentationId"]) && $presentationData["presentationId"] ? $presentationData["presentationId"] : $presentationId . ':batchUpdate',
                body: json_encode($requests),
            );
            // Return response
            return json_decode($response->getBody()->getContents(), true);
        }
        // If there are no sheets, return error
        return [
            'error' => 'Error getting presentation data'
        ];
    }

    /**
     * @param string|null $presentationId
     * @param string $objectId
     * @param string $text
     * @param int|null $insertionIndex
     * @param Range|array|null $range
     * @param TableCellLocation|array|null $cellLocation
     * @param array|null $presentationData
     * @param bool $checkPresentation
     * @param bool $getRequestObjectOnly
     * @return array
     * @throws GuzzleException
     */
    public function updateText(
        string $objectId,
        string $presentationId = null,
        string $text = "",
        int $insertionIndex = null,
        Range|array|null $range = null,
        TableCellLocation|array|null $cellLocation = null,
        array $presentationData = null,
        bool $checkPresentation = false,
        bool $getRequestObjectOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];
        // Get spreadsheet data if not provided
        if (is_null($presentationData) && $checkPresentation) {
            $presentationData = $this->getPresentationData($presentationId);
        }
        if (isset($presentationData["presentationId"]) || !$checkPresentation) {
            // Get CreateSlide request
            $deleteTextRequest = $this->deleteText(
                objectId: $objectId,
                range: $range,
                cellLocation: $cellLocation,
                getRequestObjectOnly: true,
            );
            // Get CreateSlide request
            $insertTextRequest = $this->insertText(
                objectId: $objectId,
                text: $text,
                insertionIndex: $insertionIndex,
                cellLocation: $cellLocation,
                getRequestObjectOnly: true,
            );
            // Consolidate requests
            $updateTextRequest =  [
                ...$deleteTextRequest,
                ...$insertTextRequest,
            ];
            // Return just the object if requested
            if ($getRequestObjectOnly) {
                return $updateTextRequest;
            }
            // Push requests
            $requests['requests'][] = $updateTextRequest;
            // Exec request
            $response = $this->performRequest(
                method: "POST",
                endpoint: isset($presentationData["presentationId"]) && $presentationData["presentationId"] ? $presentationData["presentationId"] : $presentationId . ':batchUpdate',
                body: json_encode($requests),
            );
            // Return response
            return json_decode($response->getBody()->getContents(), true);
        }
        // If there are no sheets, return error
        return [
            'error' => 'Error getting presentation data'
        ];
    }

    /**
     * @param string|null $presentationId
     * @param string $objectId
     * @param TextStyle|array|null $style
     * @param Range|array|null $textRange
     * @param TableCellLocation|array|null $cellLocation
     * @param string $fields
     * @param array|null $presentationData
     * @param bool $checkPresentation
     * @param bool $getRequestObjectOnly
     * @return array
     * @throws GuzzleException
     */
    public function updateTextStyle(
        string $objectId,
        string $presentationId = null,
        TextStyle|array|null $style = null,
        Range|array|null $textRange = null,
        TableCellLocation|array|null $cellLocation = null,
        string $fields = '*',
        array $presentationData = null,
        bool $checkPresentation = false,
        bool $getRequestObjectOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];
        // Get spreadsheet data if not provided
        if (is_null($presentationData) && $checkPresentation) {
            $presentationData = $this->getPresentationData($presentationId);
        }
        if (isset($presentationData["presentationId"]) || !$checkPresentation) {
            // Get CreateSlide request
            $updateTextStyleRequest = [
                'updateTextStyle' => Helpers::getJsonableArray(new UpdateTextStyleRequest(
                    objectId: $objectId,
                    style: $style,
                    cellLocation: $cellLocation,
                    textRange: $textRange,
                    fields: $fields,
                ))
            ];
            // Return just the object if requested
            if ($getRequestObjectOnly) {
                return $updateTextStyleRequest;
            }
            // Push requests
            $requests['requests'][] = $updateTextStyleRequest;
            // Exec request
            $response = $this->performRequest(
                method: "POST",
                endpoint: isset($presentationData["presentationId"]) && $presentationData["presentationId"] ? $presentationData["presentationId"] : $presentationId . ':batchUpdate',
                body: json_encode($requests),
            );
            // Return response
            return json_decode($response->getBody()->getContents(), true);
        }
        // If there are no sheets, return error
        return [
            'error' => 'Error getting presentation data'
        ];
    }

    /**
     * @param int $rows
     * @param int $columns
     * @param string|null $objectId
     * @param string|null $presentationId
     * @param PageElementProperties|array $elementProperties
     * @param array|null $presentationData
     * @param bool $checkPresentation
     * @param bool $getRequestObjectOnly
     * @return array
     * @throws GuzzleException
     */
    public function createTable(
        int $rows,
        int $columns,
        string $objectId = null,
        string $presentationId = null,
        PageElementProperties|array $elementProperties,
        array $presentationData = null,
        bool $checkPresentation = false,
        bool $getRequestObjectOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];
        // Get spreadsheet data if not provided
        if (is_null($presentationData) && $checkPresentation) {
            $presentationData = $this->getPresentationData($presentationId);
        }
        if (isset($presentationData["presentationId"]) || !$checkPresentation) {
            // Get CreateSlide request
            $createTableRequest = [
                'createTable' => Helpers::getJsonableArray(new CreateTableRequest(
                    elementProperties: $elementProperties,
                    rows: $rows,
                    columns: $columns,
                    objectId: $objectId,
                ))
            ];
            // Return just the object if requested
            if ($getRequestObjectOnly) {
                return $createTableRequest;
            }
            // Push requests
            $requests['requests'][] = $createTableRequest;
            // Exec request
            $response = $this->performRequest(
                method: "POST",
                endpoint: isset($presentationData["presentationId"]) && $presentationData["presentationId"] ? $presentationData["presentationId"] : $presentationId . ':batchUpdate',
                body: json_encode($requests),
            );
            // Return response
            return json_decode($response->getBody()->getContents(), true);
        }
        // If there are no sheets, return error
        return [
            'error' => 'Error getting presentation data'
        ];
    }

    /**
     * @param string $objectId
     * @param array $rowIndices
     * @param TableRowProperties|array $tableRowProperties
     * @param string|null $presentationId
     * @param string $fields
     * @param array|null $presentationData
     * @param bool $checkPresentation
     * @param bool $getRequestObjectOnly
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    public function updateTableRowProperties(
        string $objectId,
        array $rowIndices,
        TableRowProperties|array $tableRowProperties,
        string $presentationId = null,
        string $fields = '*',
        array $presentationData = null,
        bool $checkPresentation = false,
        bool $getRequestObjectOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];
        // Get spreadsheet data if not provided
        if (is_null($presentationData) && $checkPresentation) {
            $presentationData = $this->getPresentationData($presentationId);
        }
        if (isset($presentationData["presentationId"]) || !$checkPresentation) {
            // Get CreateSlide request
            $updateTableRowPropertiesRequest = [
                'updateTableRowProperties' => Helpers::getJsonableArray(new UpdateTableRowPropertiesRequest(
                    objectId: $objectId,
                    rowIndices: $rowIndices,
                    tableRowProperties: $tableRowProperties,
                    fields: $fields,
                ))
            ];
            // Return just the object if requested
            if ($getRequestObjectOnly) {
                return $updateTableRowPropertiesRequest;
            }
            // Push requests
            $requests['requests'][] = $updateTableRowPropertiesRequest;
            // Exec request
            $response = $this->performRequest(
                method: "POST",
                endpoint: isset($presentationData["presentationId"]) && $presentationData["presentationId"] ? $presentationData["presentationId"] : $presentationId . ':batchUpdate',
                body: json_encode($requests),
            );
            // Return response
            return json_decode($response->getBody()->getContents(), true);
        }
        // If there are no sheets, return error
        return [
            'error' => 'Error getting presentation data'
        ];
    }

    /**
     * @param string $objectId
     * @param array $columnIndices
     * @param TableColumnProperties|array $tableColumnProperties
     * @param string|null $presentationId
     * @param string $fields
     * @param array|null $presentationData
     * @param bool $checkPresentation
     * @param bool $getRequestObjectOnly
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    public function updateTableColumnProperties(
        string $objectId,
        array $columnIndices,
        TableColumnProperties|array $tableColumnProperties,
        string $presentationId = null,
        string $fields = '*',
        array $presentationData = null,
        bool $checkPresentation = false,
        bool $getRequestObjectOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];
        // Get spreadsheet data if not provided
        if (is_null($presentationData) && $checkPresentation) {
            $presentationData = $this->getPresentationData($presentationId);
        }
        if (isset($presentationData["presentationId"]) || !$checkPresentation) {
            // Get CreateSlide request
            $updateTableColumnPropertiesRequest = [
                'updateTableColumnProperties' => Helpers::getJsonableArray(new UpdateTableColumnPropertiesRequest(
                    objectId: $objectId,
                    columnIndices: $columnIndices,
                    tableColumnProperties: $tableColumnProperties,
                    fields: $fields,
                ))
            ];
            // Return just the object if requested
            if ($getRequestObjectOnly) {
                return $updateTableColumnPropertiesRequest;
            }
            // Push requests
            $requests['requests'][] = $updateTableColumnPropertiesRequest;
            // Exec request
            $response = $this->performRequest(
                method: "POST",
                endpoint: isset($presentationData["presentationId"]) && $presentationData["presentationId"] ? $presentationData["presentationId"] : $presentationId . ':batchUpdate',
                body: json_encode($requests),
            );
            // Return response
            return json_decode($response->getBody()->getContents(), true);
        }
        // If there are no sheets, return error
        return [
            'error' => 'Error getting presentation data'
        ];
    }

    /**
     * @param string $objectId
     * @param TableCellProperties|array $tableCellProperties
     * @param string|null $presentationId
     * @param TableRange|array|null $tableRange
     * @param string $fields
     * @param array|null $presentationData
     * @param bool $checkPresentation
     * @param bool $getRequestObjectOnly
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    public function updateTableCellProperties(
        string $objectId,
        TableCellProperties|array $tableCellProperties,
        string $presentationId = null,
        TableRange|array|null $tableRange  = null,
        string $fields = '*',
        array $presentationData = null,
        bool $checkPresentation = false,
        bool $getRequestObjectOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];
        // Get spreadsheet data if not provided
        if (is_null($presentationData) && $checkPresentation) {
            $presentationData = $this->getPresentationData($presentationId);
        }
        if (isset($presentationData["presentationId"]) || !$checkPresentation) {
            // Get CreateSlide request
            $updateTableCellPropertiesRequest = [
                'updateTableCellProperties' => Helpers::getJsonableArray(new UpdateTableCellPropertiesRequest(
                    objectId: $objectId,
                    tableCellProperties: $tableCellProperties,
                    fields: $fields,
                    tableRange: $tableRange,
                ))
            ];
            // Return just the object if requested
            if ($getRequestObjectOnly) {
                return $updateTableCellPropertiesRequest;
            }
            // Push requests
            $requests['requests'][] = $updateTableCellPropertiesRequest;
            // Exec request
            $response = $this->performRequest(
                method: "POST",
                endpoint: isset($presentationData["presentationId"]) && $presentationData["presentationId"] ? $presentationData["presentationId"] : $presentationId . ':batchUpdate',
                body: json_encode($requests),
            );
            // Return response
            return json_decode($response->getBody()->getContents(), true);
        }
        // If there are no sheets, return error
        return [
            'error' => 'Error getting presentation data'
        ];
    }

    /**
     * @param PageElementProperties|array $elementProperties
     * @param string $spreadsheetId
     * @param int $chartId
     * @param string|null $presentationId
     * @param LinkingMode|string $linkingMode
     * @param string|null $objectId
     * @param array|null $presentationData
     * @param bool $checkPresentation
     * @param bool $getRequestObjectOnly
     * @return array
     * @throws GuzzleException
     */
    public function createSheetsChart(
        PageElementProperties|array $elementProperties,
        string $spreadsheetId,
        int $chartId,
        string $presentationId = null,
        LinkingMode|string $linkingMode = LinkingMode::LINKED,
        string $objectId = null,
        array $presentationData = null,
        bool $checkPresentation = false,
        bool $getRequestObjectOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];
        // Get spreadsheet data if not provided
        if (is_null($presentationData) && $checkPresentation) {
            $presentationData = $this->getPresentationData($presentationId);
        }
        if (isset($presentationData["presentationId"]) || !$checkPresentation) {
            // Get CreateSlide request
            $createSheetsChartRequest = [
                'createSheetsChart' => Helpers::getJsonableArray(new CreateSheetsChartRequest(
                    elementProperties: $elementProperties,
                    spreadsheetId : $spreadsheetId,
                    chartId: $chartId,
                    linkingMode : $linkingMode,
                    objectId  : $objectId,
                ))
            ];
            // Return just the object if requested
            if ($getRequestObjectOnly) {
                return $createSheetsChartRequest;
            }
            // Push requests
            $requests['requests'][] = $createSheetsChartRequest;
            // Exec request
            $response = $this->performRequest(
                method: "POST",
                endpoint: isset($presentationData["presentationId"]) && $presentationData["presentationId"] ? $presentationData["presentationId"] : $presentationId . ':batchUpdate',
                body: json_encode($requests),
            );
            // Return response
            return json_decode($response->getBody()->getContents(), true);
        }
        // If there are no sheets, return error
        return [
            'error' => 'Error getting presentation data'
        ];
    }

    /**
     * @param PageElementProperties|array $elementProperties
     * @param string $url
     * @param string|null $presentationId
     * @param string|null $objectId
     * @param array|null $presentationData
     * @param bool $checkPresentation
     * @param bool $getRequestObjectOnly
     * @return array
     * @throws GuzzleException
     */
    public function createImage(
        PageElementProperties|array $elementProperties,
        string $url,
        string $presentationId = null,
        string $objectId = null,
        array $presentationData = null,
        bool $checkPresentation = false,
        bool $getRequestObjectOnly = false,
    ): array {
        // Initialize requests array
        $requests = [
            'requests' => []
        ];
        // Get spreadsheet data if not provided
        if (is_null($presentationData) && $checkPresentation) {
            $presentationData = $this->getPresentationData($presentationId);
        }
        if (isset($presentationData["presentationId"]) || !$checkPresentation) {
            // Get CreateSlide request
            $createImageRequest = [
                'createImage' => Helpers::getJsonableArray(new CreateImageRequest(
                    elementProperties: $elementProperties,
                    url : $url,
                    objectId: $objectId,
                ))
            ];
            // Return just the object if requested
            if ($getRequestObjectOnly) {
                return $createImageRequest;
            }
            // Push requests
            $requests['requests'][] = $createImageRequest;
            // Exec request
            $response = $this->performRequest(
                method: "POST",
                endpoint: isset($presentationData["presentationId"]) && $presentationData["presentationId"] ? $presentationData["presentationId"] : $presentationId . ':batchUpdate',
                body: json_encode($requests),
            );
            // Return response
            return json_decode($response->getBody()->getContents(), true);
        }
        // If there are no sheets, return error
        return [
            'error' => 'Error getting presentation data'
        ];
    }
}
