<?php

namespace Anibalealvarezs\GoogleApi\Services\SearchConsole;

use Anibalealvarezs\GoogleApi\Google\GoogleApi;
use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Services\SearchConsole\Classes\DimensionFilterGroup;
use Anibalealvarezs\GoogleApi\Services\SearchConsole\Enums\AggregationType;
use Anibalealvarezs\GoogleApi\Services\SearchConsole\Enums\DataState;
use Anibalealvarezs\GoogleApi\Services\SearchConsole\Enums\GroupType;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;

class SearchConsoleApi extends GoogleApi
{
    protected ?Client $defaultSitemapClient;

    /**
     * @param string $redirectUrl
     * @param string $clientId
     * @param string $clientSecret
     * @param string $refreshToken
     * @param string $userId
     * @param array $scopes
     * @param string $token
     * @param Client|null $guzzleClient
     * @param Client|null $defaultSitemapClient
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
        ?Client $guzzleClient = null,
        ?Client $defaultSitemapClient = null
    ) {
        parent::__construct(
            baseUrl: "https://www.googleapis.com/webmasters/v3/",
            redirectUrl: $redirectUrl,
            clientId: $clientId,
            clientSecret: $clientSecret,
            refreshToken: $refreshToken,
            userId: $userId,
            scopes: ($scopes ?: ["https://www.googleapis.com/auth/webmasters"]),
            token: $token,
            guzzleClient: $guzzleClient,
        );
        $this->setDefaultSitemapClient($defaultSitemapClient);
    }

    /**
     * Get the default Guzzle client for sitemap validation.
     *
     * @return Client|null
     */
    public function getDefaultSitemapClient(): ?Client
    {
        return $this->defaultSitemapClient;
    }

    /**
     * Set the default Guzzle client for sitemap validation.
     *
     * @param Client|null $client
     * @return void
     */
    public function setDefaultSitemapClient(?Client $client): void
    {
        $this->defaultSitemapClient = $client;
    }

    /**
     * @param string $siteUrl
     * @param string $startDate
     * @param string $endDate
     * @param int $rowLimit
     * @param int $startRow
     * @param DataState $dataState
     * @param array|null $dimensions
     * @param string|null $type
     * @param DimensionFilterGroup[]|null $dimensionFilterGroups
     * @param AggregationType $aggregationType
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    public function getSearchQueryResults(
        string $siteUrl,
        string $startDate,
        string $endDate,
        int $rowLimit = 25000,
        int $startRow = 0,
        DataState $dataState = DataState::ALL,
        ?array $dimensions = null,
        string $type = null,
        ?array $dimensionFilterGroups = null,
        AggregationType $aggregationType = AggregationType::AUTO,
    ): array {
        if (!Helpers::isValidSearchConsoleUrl($siteUrl)) {
            throw new InvalidArgumentException("Invalid site URL: " . $siteUrl . ". It must comply with either of the following formats: https://example.com or sc-domain:example.com");
        }

        $request = [
            "startDate" => $startDate,
            "endDate" => $endDate,
            "rowLimit" => $rowLimit,
            "startRow" => $startRow,
            "dataState" => $dataState->value,
        ];
        if ($dimensions) {
            $request["dimensions"] = $dimensions;
        }
        if ($type) {
            $request["type"] = $type;
        }
        if ($dimensionFilterGroups) {
            $groups = [];
            foreach($dimensionFilterGroups as $dimensionFilterGroup) {
                if (!empty($dimensionFilterGroup["filters"])) {
                    $groups[] = Helpers::getJsonableArray(new DimensionFilterGroup(
                        filters: $dimensionFilterGroup["filters"],
                        groupType: $dimensionFilterGroup["groupType"] ?? GroupType::AND,
                    ));
                }
            }
            $request["dimensionFilterGroups"] = $groups;
        }
        if ($aggregationType) {
            $request["aggregationType"] = $aggregationType->value;
        }
        // Request the spreadsheet data
        $response = $this->performRequest(
            method: "POST",
            endpoint: "sites/" . urlencode($siteUrl) . "/searchAnalytics/query",
            body: json_encode($request),
        );
        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $siteUrl
     * @param string $startDate
     * @param string $endDate
     * @param int $rowLimit
     * @param int $startRow
     * @param DataState $dataState
     * @param array|null $dimensions
     * @param string|null $type
     * @param DimensionFilterGroup[]|null $dimensionFilterGroups
     * @param AggregationType $aggregationType
     * @return array
     * @throws GuzzleException
     */
    public function getAllSearchQueryResults(
        string $siteUrl,
        string $startDate,
        string $endDate,
        int $rowLimit = 25000,
        int $startRow = 0,
        DataState $dataState = DataState::ALL,
        ?array $dimensions = null,
        string $type = null,
        ?array $dimensionFilterGroups = null,
        AggregationType $aggregationType = AggregationType::AUTO,
    ): array {
        $rows = [];
        $params = [
            "siteUrl" => $siteUrl,
            "startDate" => $startDate,
            "endDate" => $endDate,
            "rowLimit" => $rowLimit,
            "startRow" => $startRow,
            "dataState" => $dataState,
            "dimensions" => $dimensions,
            "type" => $type,
            "dimensionFilterGroups" => $dimensionFilterGroups,
            "aggregationType" => $aggregationType,
        ];
        do {
            // Request the spreadsheet data
            $response = $this->getSearchQueryResults(...$params);
            $rows = array_merge($rows, $response["rows"] ?? []);
            $params["startRow"] += $rowLimit;
        } while (count($response["rows"]) == $rowLimit);
        // Return response
        return ['rows' => $rows];
    }

    /**
     * @throws GuzzleException
     */
    public function getSites(): array
    {
        $response = $this->performRequest(
            method: "GET",
            endpoint: "sites",
        );
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws GuzzleException
     */
    public function getSite(string $siteUrl): array
    {
        if (!Helpers::isValidSearchConsoleUrl($siteUrl)) {
            throw new InvalidArgumentException("Invalid site URL: " . $siteUrl . ". It must comply with either of the following formats: https://example.com or sc-domain:example.com");
        }

        $response = $this->performRequest(
            method: "GET",
            endpoint: "sites/" . urlencode($siteUrl),
        );
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws GuzzleException
     */
    public function addSite(string $siteUrl): array
    {
        if (!Helpers::isValidSearchConsoleUrl($siteUrl)) {
            throw new InvalidArgumentException("Invalid site URL: " . $siteUrl . ". It must comply with either of the following formats: https://example.com or sc-domain:example.com");
        }

        $response = $this->performRequest(
            method: "PUT",
            endpoint: "sites/" . urlencode($siteUrl),
        );
        return json_decode($response->getBody()->getContents() ?: '[]', true);
    }

    /**
     * @throws GuzzleException
     */
    public function removeSite(string $siteUrl): array
    {
        if (!Helpers::isValidSearchConsoleUrl($siteUrl)) {
            throw new InvalidArgumentException("Invalid site URL: " . $siteUrl . ". It must comply with either of the following formats: https://example.com or sc-domain:example.com");
        }

        $response = $this->performRequest(
            method: "DELETE",
            endpoint: "sites/" . urlencode($siteUrl),
        );
        return json_decode($response->getBody()->getContents() ?: '[]', true);
    }

    /**
     * @throws GuzzleException
     */
    public function getSitemaps(string $siteUrl): array
    {
        if (!Helpers::isValidSearchConsoleUrl($siteUrl)) {
            throw new InvalidArgumentException("Invalid site URL: " . $siteUrl . ". It must comply with either of the following formats: https://example.com or sc-domain:example.com");
        }

        $response = $this->performRequest(
            method: "GET",
            endpoint: "sites/" . urlencode($siteUrl) . "/sitemaps",
        );
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws GuzzleException
     */
    public function getSitemap(string $siteUrl, string $sitemap): array
    {
        if (!Helpers::isValidSearchConsoleUrl($siteUrl)) {
            throw new InvalidArgumentException("Invalid site URL: " . $siteUrl . ". It must comply with either of the following formats: https://example.com or sc-domain:example.com");
        }

        $response = $this->performRequest(
            method: "GET",
            endpoint: "sites/" . urlencode($siteUrl) . "/sitemaps/" . urlencode($sitemap),
        );
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws GuzzleException
     */
    public function addSitemap(string $siteUrl, string $sitemap, ?Client $client = null): array
    {
        if (!Helpers::isValidSearchConsoleUrl($siteUrl)) {
            throw new InvalidArgumentException("Invalid site URL: " . $siteUrl . ". It must comply with either of the following formats: https://example.com or sc-domain:example.com");
        }

        if (!Helpers::isSitemapUrl($sitemap, $client)) {
            throw new InvalidArgumentException("Invalid sitemap URL: " . $sitemap . ". Either the URL is not valid, or the URL is not a sitemap or the sitemap is not available.");
        }

        $response = $this->performRequest(
            method: "PUT",
            endpoint: "sites/" . urlencode($siteUrl) . "/sitemaps/" . urlencode($sitemap),
        );
        return json_decode($response->getBody()->getContents() ?: '[]', true);
    }

    /**
     * @throws GuzzleException
     */
    public function removeSitemap(string $siteUrl, string $sitemap): array
    {
        if (!Helpers::isValidSearchConsoleUrl($siteUrl)) {
            throw new InvalidArgumentException("Invalid site URL: " . $siteUrl . ". It must comply with either of the following formats: https://example.com or sc-domain:example.com");
        }

        $response = $this->performRequest(
            method: "DELETE",
            endpoint: "sites/" . urlencode($siteUrl) . "/sitemaps/" . urlencode($sitemap),
        );
        return json_decode($response->getBody()->getContents() ?: '[]', true);
    }

    /**
     * @throws GuzzleException
     */
    public function inspectUrl(string $siteUrl, string $url, string $languageCode = "en-US"): array
    {
        if (!Helpers::isValidSearchConsoleUrl($siteUrl)) {
            throw new InvalidArgumentException("Invalid site URL: " . $siteUrl . ". It must comply with either of the following formats: https://example.com or sc-domain:example.com");
        }

        $baseUrl = $this->getBaseUrl();
        $this->setBaseUrl('https://searchconsole.googleapis.com/v1/');

        $response = $this->performRequest(
            method: "POST",
            endpoint: "urlInspection/index:inspect",
            body: json_encode([
                "inspectionUrl" => $url,
                "siteUrl" => $siteUrl,
                "languageCode" => $languageCode,
            ]),
        );

        $this->setBaseUrl($baseUrl);

        return json_decode($response->getBody()->getContents(), true);
    }
}
