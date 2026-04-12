<?php

namespace Anibalealvarezs\GoogleApi\Services\BusinessPerformance;

use Anibalealvarezs\GoogleApi\Google\GoogleApi;
use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BusinessPerformanceApi extends GoogleApi
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
            baseUrl: "https://businessprofileperformance.googleapis.com/v1/",
            redirectUrl: $redirectUrl,
            clientId: $clientId,
            clientSecret: $clientSecret,
            refreshToken: $refreshToken,
            userId: $userId,
            scopes: Helpers::parseScopes($scopes, ["https://www.googleapis.com/auth/business.manage"]),
            token: $token,
            guzzleClient: $guzzleClient,
            tokenPath: $tokenPath,
        );
    }

    /**
     * @param string $locationName Format: locations/{location_id}
     * @param array $metrics List of DailyMetric enum values
     * @param string $startDate ISO date YYYY-MM-DD
     * @param string $endDate ISO date YYYY-MM-DD
     * @return array
     * @throws GuzzleException
     * @link https://developers.google.com/my-business/reference/businessprofileperformance/rest/v1/locations/fetchDailyMetricsTimeSeries
     */
    public function fetchDailyMetricsTimeSeries(
        string $locationName,
        array $metrics,
        string $startDate,
        string $endDate
    ): array {
        $start = explode('-', $startDate);
        $end = explode('-', $endDate);

        $query = [
            'dailyRange.startDate.year' => $start[0],
            'dailyRange.startDate.month' => $start[1],
            'dailyRange.startDate.day' => $start[2],
            'dailyRange.endDate.year' => $end[0],
            'dailyRange.endDate.month' => $end[1],
            'dailyRange.endDate.day' => $end[2],
        ];

        $query['dailyMetric'] = $metrics;

        $response = $this->performRequest(
            method: "GET",
            endpoint: $locationName . ":fetchDailyMetricsTimeSeries",
            query: $query,
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Fetches daily metrics time series for multiple locations.
     *
     * @param array $locationNames Array of location names (format: locations/{location_id})
     * @param array $metrics
     * @param string $startDate
     * @param string $endDate
     * @return array Map of locationName => metricsData
     * @throws GuzzleException
     */
    public function fetchDailyMetricsTimeSeriesForMultipleLocations(
        array $locationNames,
        array $metrics,
        string $startDate,
        string $endDate
    ): array {
        $results = [];
        foreach ($locationNames as $locationName) {
            $results[$locationName] = $this->fetchDailyMetricsTimeSeries($locationName, $metrics, $startDate, $endDate);
        }
        return $results;
    }

    /**
     * Fetches daily metrics time series for multiple locations and processes them with a callback.
     *
     * @param array $locationNames
     * @param array $metrics
     * @param string $startDate
     * @param string $endDate
     * @param callable $callback
     * @return void
     * @throws GuzzleException
     */
    public function fetchDailyMetricsTimeSeriesAndProcess(
        array $locationNames,
        array $metrics,
        string $startDate,
        string $endDate,
        callable $callback
    ): void {
        foreach ($locationNames as $locationName) {
            $data = $this->fetchDailyMetricsTimeSeries($locationName, $metrics, $startDate, $endDate);
            $callback($locationName, $data);
        }
    }
}
