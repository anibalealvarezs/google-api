<?php

namespace Anibalealvarezs\GoogleApi\Services\YouTubeAnalytics;

use Anibalealvarezs\GoogleApi\Google\GoogleApi;
use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class YouTubeAnalyticsApi extends GoogleApi
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
            baseUrl: "https://youtubeanalytics.googleapis.com/v2/",
            redirectUrl: $redirectUrl,
            clientId: $clientId,
            clientSecret: $clientSecret,
            refreshToken: $refreshToken,
            userId: $userId,
            scopes: Helpers::parseScopes($scopes, ["https://www.googleapis.com/auth/yt-analytics.readonly"]),
            token: $token,
            guzzleClient: $guzzleClient,
            tokenPath: $tokenPath,
        );
    }

    /**
     * @param array $params
     * @return array
     * @throws GuzzleException
     * @link https://developers.google.com/youtube/analytics/reference/reports/query
     */
    public function query(array $params): array
    {
        $response = $this->performRequest(
            method: "GET",
            endpoint: "reports",
            query: $params,
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Helper to run a simple report for the authenticated channel.
     *
     * @param string $startDate
     * @param string $endDate
     * @param array $metrics
     * @param array|null $dimensions
     * @param string|null $filters
     * @return array
     * @throws GuzzleException
     */
    public function getChannelReport(
        string $startDate,
        string $endDate,
        array $metrics = ['views', 'likes', 'estimatedMinutesWatched'],
        ?array $dimensions = ['day'],
        ?string $filters = null
    ): array {
        $params = [
            'ids' => 'channel==MINE',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'metrics' => implode(',', $metrics),
        ];

        if ($dimensions) {
            $params['dimensions'] = implode(',', $dimensions);
            $params['sort'] = $dimensions[0];
        }

        if ($filters) {
            $params['filters'] = $filters;
        }

        return $this->query($params);
    }

    /**
     * Fetches all rows from a YouTube Analytics report by automatically handling pagination.
     *
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function queryAll(array $params): array
    {
        $rows = [];
        $params['maxResults'] = $params['maxResults'] ?? 10000;
        $params['startIndex'] = $params['startIndex'] ?? 1;

        do {
            $response = $this->query($params);
            if (empty($response['rows'])) {
                break;
            }
            $rows = array_merge($rows, $response['rows']);
            $params['startIndex'] += count($response['rows']);
        } while (count($response['rows']) >= $params['maxResults']);

        $response['rows'] = $rows;
        return $response;
    }

    /**
     * Fetches all rows from a YouTube Analytics report and processes them with a callback.
     *
     * @param array $params
     * @param callable $callback
     * @return void
     * @throws GuzzleException
     */
    public function queryAllAndProcess(array $params, callable $callback): void
    {
        $params['maxResults'] = $params['maxResults'] ?? 10000;
        $params['startIndex'] = $params['startIndex'] ?? 1;

        do {
            $response = $this->query($params);
            if (empty($response['rows'])) {
                break;
            }
            $callback($response['rows']);
            $params['startIndex'] += count($response['rows']);
        } while (count($response['rows']) >= $params['maxResults']);
    }
}
