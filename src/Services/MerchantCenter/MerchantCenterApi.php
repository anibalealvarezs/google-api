<?php

namespace Anibalealvarezs\GoogleApi\Services\MerchantCenter;

use Anibalealvarezs\GoogleApi\Google\GoogleApi;
use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class MerchantCenterApi extends GoogleApi
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
            baseUrl: "https://shoppingcontent.googleapis.com/content/v2.1/",
            redirectUrl: $redirectUrl,
            clientId: $clientId,
            clientSecret: $clientSecret,
            refreshToken: $refreshToken,
            userId: $userId,
            scopes: Helpers::parseScopes($scopes, ["https://www.googleapis.com/auth/content"]),
            token: $token,
            guzzleClient: $guzzleClient,
            tokenPath: $tokenPath,
        );
    }

    /**
     * @param string $merchantId
     * @param string $query Merchant Center Query Language (MCQL)
     * @return array
     * @throws GuzzleException
     * @link https://developers.google.com/shopping-content/reference/rest/v2.1/reports/search
     */
    public function searchReport(string $merchantId, string $query): array
    {
        $payload = [
            "query" => $query,
        ];

        $response = $this->performRequest(
            method: "POST",
            endpoint: $merchantId . "/reports/search",
            body: json_encode($payload),
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Helper to get simple product performance.
     *
     * @param string $merchantId
     * @param string $startDate YYYY-MM-DD
     * @param string $endDate YYYY-MM-DD
     * @return array
     * @throws GuzzleException
     */
    public function getProductPerformance(string $merchantId, string $startDate, string $endDate): array
    {
        $query = "SELECT product_view.id, product_view.title, metrics.impressions, metrics.clicks " .
                 "FROM product_performance_view " .
                 "WHERE segments.date >= '{$startDate}' AND segments.date <= '{$endDate}'";

        return $this->searchReport($merchantId, $query);
    }

    /**
     * Fetches all results from a Merchant Center search by automatically handling pagination.
     *
     * @param string $merchantId
     * @param string $query
     * @param int $pageSize
     * @return array
     * @throws GuzzleException
     */
    public function searchAllReports(string $merchantId, string $query, int $pageSize = 1000): array
    {
        $results = [];
        $pageToken = null;

        do {
            $payload = [
                "query" => $query,
                "pageSize" => $pageSize,
            ];
            if ($pageToken) {
                $payload["pageToken"] = $pageToken;
            }

            $response = $this->performRequest(
                method: "POST",
                endpoint: $merchantId . "/reports/search",
                body: json_encode($payload),
            );

            $data = json_decode($response->getBody()->getContents(), true);
            if (!empty($data['results'])) {
                $results = array_merge($results, $data['results']);
            }
            $pageToken = $data['nextPageToken'] ?? null;
        } while ($pageToken);

        $data['results'] = $results;
        unset($data['nextPageToken']);
        return $data;
    }

    /**
     * Fetches all results from a Merchant Center search and processes them with a callback.
     *
     * @param string $merchantId
     * @param string $query
     * @param callable $callback
     * @param int $pageSize
     * @return void
     * @throws GuzzleException
     */
    public function searchAllReportsAndProcess(string $merchantId, string $query, callable $callback, int $pageSize = 1000): void
    {
        $pageToken = null;

        do {
            $payload = [
                "query" => $query,
                "pageSize" => $pageSize,
            ];
            if ($pageToken) {
                $payload["pageToken"] = $pageToken;
            }

            $response = $this->performRequest(
                method: "POST",
                endpoint: $merchantId . "/reports/search",
                body: json_encode($payload),
            );

            $data = json_decode($response->getBody()->getContents(), true);
            if (!empty($data['results'])) {
                $callback($data['results']);
            }
            $pageToken = $data['nextPageToken'] ?? null;
        } while ($pageToken);
    }
}
