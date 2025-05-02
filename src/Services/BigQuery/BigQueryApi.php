<?php

namespace Anibalealvarezs\GoogleApi\Services\BigQuery;

use Anibalealvarezs\GoogleApi\Google\GoogleApi;
use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Services\BigQuery\Classes\DataFormatOptions;
use Anibalealvarezs\GoogleApi\Services\BigQuery\Classes\Query\QueryRequest;
use Anibalealvarezs\GoogleApi\Services\BigQuery\Enums\TableMetadataView;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BigQueryApi extends GoogleApi
{
    /**
     * @param string $redirectUrl
     * @param string $clientId
     * @param string $clientSecret
     * @param string $refreshToken
     * @param string $userId
     * @param array $scopes
     * @param string $token
     * @param Client|null $guzzleClient
     * @throws Exception
     */
    public function __construct(
        string $redirectUrl,
        string $clientId,
        string $clientSecret,
        string $refreshToken,
        string $userId,
        array $scopes = [],
        string $token = "",
        ?Client $guzzleClient = null
    ) {
        parent::__construct(
            baseUrl: "https://bigquery.googleapis.com/bigquery/v2/projects/",
            redirectUrl: $redirectUrl,
            clientId: $clientId,
            clientSecret: $clientSecret,
            refreshToken: $refreshToken,
            userId: $userId,
            scopes: ($scopes ?: [
                "https://www.googleapis.com/auth/bigquery",
            ]),
            token: $token,
            guzzleClient: $guzzleClient,
        );
    }

    /**
     * @param string $projectId
     * @param string $jobId
     * @param int $startIndex
     * @param string|null $pageToken
     * @param int $maxResults
     * @param string|null $timeoutMs
     * @param string $location
     * @param DataFormatOptions|null $formatOptions
     * @return array
     * @throws GuzzleException
     */
    public function getQueryResults(
        string $projectId,
        string $jobId,
        int $startIndex = 0,
        ?string $pageToken = null,
        int $maxResults = 100000,
        ?string $timeoutMs = null, // Measured in milliseconds
        string $location = 'us-central1',
        ?DataFormatOptions $formatOptions = null,
    ): array {
        $query = [
            "startIndex" => $startIndex,
            "pageToken" => $pageToken,
            "maxResults" => $maxResults,
            "timeoutMs" => $timeoutMs,
            "location" => $location,
        ];
        if ($formatOptions) {
            $query["formatOptions"] = $formatOptions->toJson();
        }
        // Request the spreadsheet data
        $response = $this->performRequest(
            method: "GET",
            endpoint: $projectId . "/queries/" . $jobId,
            query: $query,
        );
        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $projectId
     * @param string $jobId
     * @param int $startIndex
     * @param string|null $timeoutMs
     * @param string $location
     * @param DataFormatOptions|null $formatOptions
     * @return array
     * @throws GuzzleException
     */
    public function getAllQueryResults(
        string $projectId,
        string $jobId,
        int $startIndex = 0,
        ?string $timeoutMs = null, // Measured in milliseconds
        string $location = 'us-central1',
        ?DataFormatOptions $formatOptions = null,
    ): array {
        $rows = [];
        $params = [
            "projectId" => $projectId,
            "jobId" => $jobId,
            "startIndex" => $startIndex,
            "pageToken" => null,
            "timeoutMs" => $timeoutMs,
            "location" => $location,
            "formatOptions" => $formatOptions,
        ];
        do {
            // Request the spreadsheet data
            $response = $this->getQueryResults(...$params);
            $rows = array_merge($rows, $response["rows"] ?? []);
            $params["pageToken"] = isset($response["pageToken"]) && $response["pageToken"] ? $response["pageToken"] : null;
        } while ($params["pageToken"]);
        // Return response
        return ['rows' => $rows];
    }

    /**
     * @param string $projectId
     * @param string $datasetId
     * @param string $tableId
     * @param string|null $selectedFields
     * @param TableMetadataView $view
     * @return array
     * @throws GuzzleException
     */
    public function getTable(
        string $projectId,
        string $datasetId,
        string $tableId,
        ?string $selectedFields = null,
        TableMetadataView $view = TableMetadataView::STORAGE_STATS,
    ): array {
        $query = [
            "view" => $view,
        ];
        if ($selectedFields) {
            $query["selectedFields"] = $selectedFields;
        }
        // Request the spreadsheet data
        $response = $this->performRequest(
            method: "GET",
            endpoint: $projectId . "/datasets/" . $datasetId . "/tables/" . $tableId,
            query: $query,
        );
        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $projectId
     * @param string $datasetId
     * @param string $tableId
     * @param int $startIndex
     * @param string|null $pageToken
     * @param int $maxResults
     * @param string|null $selectedFields
     * @param DataFormatOptions|null $formatOptions
     * @return array
     * @throws GuzzleException
     */
    public function getTableData(
        string $projectId,
        string $datasetId,
        string $tableId,
        int $startIndex = 0,
        ?string $pageToken = null,
        int $maxResults = 100000,
        ?string $selectedFields = null,
        ?DataFormatOptions $formatOptions = null,
    ): array {
        $query = [
            "startIndex" => $startIndex,
            "pageToken" => $pageToken,
            "maxResults" => $maxResults,
        ];
        if ($selectedFields) {
            $query["selectedFields"] = $selectedFields;
        }
        if ($formatOptions) {
            $query["formatOptions"] = $formatOptions->toJson();
        }
        // Request the spreadsheet data
        $response = $this->performRequest(
            method: "GET",
            endpoint: $projectId . "/datasets/" . $datasetId . "/tables/" . $tableId . "/data",
            query: $query,
        );
        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $projectId
     * @param string $datasetId
     * @param string $tableId
     * @param int $startIndex
     * @param string|null $selectedFields
     * @param DataFormatOptions|null $formatOptions
     * @return array
     * @throws GuzzleException
     */
    public function getFullTableData(
        string $projectId,
        string $datasetId,
        string $tableId,
        int $startIndex = 0,
        ?string $selectedFields = null,
        ?DataFormatOptions $formatOptions = null,
    ): array {
        $rows = [];
        $params = [
            "projectId" => $projectId,
            "datasetId" => $datasetId,
            "tableId" => $tableId,
            "startIndex" => $startIndex,
            "pageToken" => null,
            "selectedFields" => $selectedFields,
            "formatOptions" => $formatOptions,
        ];
        do {
            // Request the spreadsheet data
            $response = $this->getTableData(...$params);
            $rows = array_merge($rows, $response["rows"] ?? []);
            $params["pageToken"] = isset($response["pageToken"]) && $response["pageToken"] ? $response["pageToken"] : null;
        } while ($params["pageToken"]);
        // Return response
        return ['rows' => $rows];
    }

    /**
     * @param string $projectId
     * @param QueryRequest $queryRequest
     * @return array
     * @throws GuzzleException
     */
    public function queryJob(
        string $projectId,
        QueryRequest $queryRequest,
    ): array {
        // Build request
        $request = Helpers::getJsonableArray($queryRequest);
        // Request the query
        $response = $this->performRequest(
            method: "POST",
            endpoint: $projectId . "/queries",
            body: json_encode($request),
        );
        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $projectId
     * @param bool $allUsers
     * @param int|null $maxResults
     * @param string|null $minCreationTime
     * @param string|null $maxCreationTime
     * @param string|null $pageToken
     * @param string|null $projection
     * @param string|null $stateFilter
     * @param string|null $parentJobId
     * @return array
     * @throws GuzzleException
     */
    public function getJobsList(
        string $projectId,
        bool $allUsers = false,
        ?int $maxResults = 10000,
        ?string $minCreationTime = null,
        ?string $maxCreationTime = null,
        ?string $pageToken = null,
        ?string $projection = null,
        ?string $stateFilter = null,
        ?string $parentJobId = null,
    ): array {
        $query = [
            "allUsers" => $allUsers,
            "maxResults" => $maxResults,
        ];
        if ($minCreationTime !== null) {
            $query["minCreationTime"] = $minCreationTime;
        };
        if ($maxCreationTime !== null) {
            $query["maxCreationTime"] = $maxCreationTime;
        };
        if ($pageToken !== null) {
            $query["pageToken"] = $pageToken;
        };
        if ($projection !== null) {
            $query["projection"] = $projection;
        };
        if ($stateFilter !== null) {
            $query["stateFilter"] = $stateFilter;
        };
        if ($parentJobId !== null) {
            $query["parentJobId"] = $parentJobId;
        };
        // Request the spreadsheet data
        $response = $this->performRequest(
            method: "GET",
            endpoint: $projectId . "/jobs",
            query: $query,
        );
        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $projectId
     * @param bool $allUsers
     * @param string|null $minCreationTime
     * @param string|null $maxCreationTime
     * @param string|null $projection
     * @param string|null $stateFilter
     * @param string|null $parentJobId
     * @return array
     * @throws GuzzleException
     */
    public function getAllJobsList(
        string $projectId,
        bool $allUsers = false,
        ?string $minCreationTime = null,
        ?string $maxCreationTime = null,
        ?string $projection = null,
        ?string $stateFilter = null,
        ?string $parentJobId = null,
    ): array {
        $jobs = [];
        $params = [
            "projectId" => $projectId,
            "allUsers" => $allUsers,
            "minCreationTime" => $minCreationTime,
            "maxCreationTime" => $maxCreationTime,
            "pageToken" => null,
            "projection" => $projection,
            "stateFilter" => $stateFilter,
            "parentJobId" => $parentJobId,
        ];
        $counter = 0;
        do {
            // Request the spreadsheet data
            $response = $this->getJobsList(...$params);
            $jobs = array_merge($jobs, $response["jobs"] ?? []);
            $params["pageToken"] = isset($response["nextPageToken"]) && $response["nextPageToken"] ? $response["nextPageToken"] : null;
            $counter++;
        } while ($params["pageToken"] && $counter < 100);
        // Return response
        return ['jobs' => $jobs];
    }
}
