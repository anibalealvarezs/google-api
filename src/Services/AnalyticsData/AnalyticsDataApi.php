<?php

namespace Anibalealvarezs\GoogleApi\Services\AnalyticsData;

use Anibalealvarezs\GoogleApi\Google\GoogleApi;
use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class AnalyticsDataApi extends GoogleApi
{
    /**
     * @param string $redirectUrl
     * @param string $clientId
     * @param string $clientSecret
     * @param string $refreshToken
     * @param string $userId
     * @param string|array $scopes
     * @param string $token
     * @param Client|null $guzzleClient
     * @param string $tokenPath
     * @throws Exception
     */
    public function __construct(
        string $redirectUrl,
        string $clientId,
        string $clientSecret,
        string $refreshToken,
        string $userId,
        string|array $scopes = [],
        string $token = "",
        ?Client $guzzleClient = null,
        string $tokenPath = ""
    ) {
        parent::__construct(
            baseUrl: "https://analyticsdata.googleapis.com/v1beta/",
            redirectUrl: $redirectUrl,
            clientId: $clientId,
            clientSecret: $clientSecret,
            refreshToken: $refreshToken,
            userId: $userId,
            scopes: Helpers::parseScopes($scopes, ["https://www.googleapis.com/auth/analytics.readonly"]),
            token: $token,
            guzzleClient: $guzzleClient,
            tokenPath: $tokenPath,
        );
    }

    /**
     * @param string $propertyId
     * @param array $payload
     * @return array
     * @throws GuzzleException
     * @link https://developers.google.com/analytics/devguides/reporting/data/v1/rest/v1beta/properties/runReport
     */
    public function runReport(string $propertyId, array $payload): array
    {
        $response = $this->performRequest(
            method: "POST",
            endpoint: "properties/" . $propertyId . ":runReport",
            body: json_encode($payload),
        );
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $propertyId
     * @param array $payload
     * @return array
     * @throws GuzzleException
     * @link https://developers.google.com/analytics/devguides/reporting/data/v1/rest/v1beta/properties/runRealtimeReport
     */
    public function runRealtimeReport(string $propertyId, array $payload): array
    {
        $response = $this->performRequest(
            method: "POST",
            endpoint: "properties/" . $propertyId . ":runRealtimeReport",
            body: json_encode($payload),
        );
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $propertyId
     * @return array
     * @throws GuzzleException
     * @link https://developers.google.com/analytics/devguides/reporting/data/v1/rest/v1beta/properties/getMetadata
     */
    public function getMetadata(string $propertyId): array
    {
        $response = $this->performRequest(
            method: "GET",
            endpoint: "properties/" . $propertyId . "/metadata"
        );
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $propertyId
     * @param array $requests Array of runReport request payloads
     * @return array
     * @throws GuzzleException
     * @link https://developers.google.com/analytics/devguides/reporting/data/v1/rest/v1beta/properties/batchRunReports
     */
    public function batchRunReports(string $propertyId, array $requests): array
    {
        $payload = [
            'requests' => $requests
        ];

        $response = $this->performRequest(
            method: "POST",
            endpoint: "properties/" . $propertyId . ":batchRunReports",
            body: json_encode($payload),
        );
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Helper to run a simple report with common parameters.
     *
     * @param string $propertyId
     * @param array $metrics
     * @param array $dimensions
     * @param string $startDate
     * @param string $endDate
     * @return array
     * @throws GuzzleException
     */
    public function runSimpleReport(
        string $propertyId,
        array $metrics,
        array $dimensions,
        string $startDate = '30daysAgo',
        string $endDate = 'today'
    ): array {
        $payload = [
            'dateRanges' => [['startDate' => $startDate, 'endDate' => $endDate]],
            'dimensions' => array_map(fn ($d) => ['name' => $d], $dimensions),
            'metrics' => array_map(fn ($m) => ['name' => $m], $metrics),
        ];

        return $this->runReport($propertyId, $payload);
    }

    /**
     * Fetches all rows from a GA4 report by automatically handling pagination.
     *
     * @param string $propertyId
     * @param array $payload
     * @return array
     * @throws GuzzleException
     */
    public function runAllReports(string $propertyId, array $payload): array
    {
        $rows = [];
        $payload['limit'] = $payload['limit'] ?? 10000;
        $payload['offset'] = $payload['offset'] ?? 0;

        do {
            $response = $this->runReport($propertyId, $payload);
            if (empty($response['rows'])) {
                break;
            }
            $rows = array_merge($rows, $response['rows']);
            $payload['offset'] += count($response['rows']);
            $rowCount = $response['rowCount'] ?? 0;
        } while (count($rows) < $rowCount && count($response['rows']) >= $payload['limit']);

        $response['rows'] = $rows;
        return $response;
    }

    /**
     * Fetches all rows from a GA4 report and processes them with a callback.
     *
     * @param string $propertyId
     * @param array $payload
     * @param callable $callback
     * @return void
     * @throws GuzzleException
     */
    public function runAllReportsAndProcess(string $propertyId, array $payload, callable $callback): void
    {
        $payload['limit'] = $payload['limit'] ?? 10000;
        $payload['offset'] = $payload['offset'] ?? 0;
        $totalProcessed = 0;

        do {
            $response = $this->runReport($propertyId, $payload);
            if (empty($response['rows'])) {
                break;
            }
            $callback($response['rows']);
            $totalProcessed += count($response['rows']);
            $payload['offset'] += count($response['rows']);
            $rowCount = $response['rowCount'] ?? 0;
        } while ($totalProcessed < $rowCount && count($response['rows']) >= $payload['limit']);
    }
}
