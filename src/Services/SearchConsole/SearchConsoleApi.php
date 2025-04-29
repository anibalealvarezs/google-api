<?php

namespace Anibalealvarezs\GoogleApi\Services\SearchConsole;

use Anibalealvarezs\GoogleApi\Google\GoogleApi;
use Anibalealvarezs\GoogleApi\Services\SearchConsole\Enums\AggregationType;
use Anibalealvarezs\GoogleApi\Services\SearchConsole\Enums\DataState;
use Anibalealvarezs\GoogleApi\Services\SearchConsole\Enums\Device;
use Anibalealvarezs\GoogleApi\Services\SearchConsole\Enums\Dimension;
use Anibalealvarezs\GoogleApi\Services\SearchConsole\Enums\GroupType;
use Anibalealvarezs\GoogleApi\Services\SearchConsole\Enums\Operator;
use Anibalealvarezs\GoogleApi\Services\SearchConsole\Enums\SearchAppearance;
use GuzzleHttp\Exception\GuzzleException;

class SearchConsoleApi extends GoogleApi
{
    /**
     * @param string $redirectUrl
     * @param string $clientId
     * @param string $clientSecret
     * @param string $refreshToken
     * @param string $userId
     * @param array $scopes
     * @throws GuzzleException
     */
    public function __construct(
        string $redirectUrl,
        string $clientId,
        string $clientSecret,
        string $refreshToken,
        string $userId,
        array $scopes = []
    ) {
        parent::__construct(
            baseUrl: "https://www.googleapis.com/webmasters/v3/",
            redirectUrl: $redirectUrl,
            clientId: $clientId,
            clientSecret: $clientSecret,
            refreshToken: $refreshToken,
            userId: $userId,
            scopes: ($scopes ?: ["https://www.googleapis.com/auth/webmasters"]),
        );
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
     * @param array|null $dimensionFilterGroups
     * @param GroupType $dimensionFilterGroupsGroupType
     * @param array|null $dimensionFilterGroupsFilters
     * @param Dimension $dimensionFilterGroupsFiltersDimension
     * @param Device|SearchAppearance|string $dimensionFilterGroupsFiltersDimensionValue
     * @param Operator $dimensionFilterGroupsFiltersDimensionOperator
     * @param AggregationType $aggregationType
     * @return array
     * @throws GuzzleException
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
        GroupType $dimensionFilterGroupsGroupType = GroupType::AND,
        ?array $dimensionFilterGroupsFilters = null,
        Dimension $dimensionFilterGroupsFiltersDimension = Dimension::COUNTRY,
        Device|SearchAppearance|string $dimensionFilterGroupsFiltersDimensionValue = Device::MOBILE,
        Operator $dimensionFilterGroupsFiltersDimensionOperator = Operator::EQUALS,
        AggregationType $aggregationType = AggregationType::AUTO,
    ): array {
        $query = [
            "startDate" => $startDate,
            "endDate" => $endDate,
            "rowLimit" => $rowLimit,
            "startRow" => $startRow,
            "dataState" => $dataState,
        ];
        if ($dimensions) {
            $query["dimensions"] = implode(",", $dimensions);
        }
        if ($type) {
            $query["type"] = $type;
        }
        if ($dimensionFilterGroups) {
            $query["dimensionFilterGroups[]"] = implode(",", $dimensionFilterGroups);
            $query["dimensionFilterGroups[].groupType"] = $dimensionFilterGroupsGroupType->value;
            if ($dimensionFilterGroupsFilters) {
                $query["dimensionFilterGroups[].filters[]"] = implode(",", $dimensionFilterGroupsFilters);
                $query["dimensionFilterGroups[].filters[].dimension"] = $dimensionFilterGroupsFiltersDimension->value;
                $query["dimensionFilterGroups[].filters[].expression"] = is_string($dimensionFilterGroupsFiltersDimensionValue) ?
                                                                            $dimensionFilterGroupsFiltersDimensionValue :
                                                                            $dimensionFilterGroupsFiltersDimensionValue->value;
                $query["dimensionFilterGroups[].filters[].operator"] = $dimensionFilterGroupsFiltersDimensionOperator->value;
            }
        }
        if ($aggregationType) {
            $query["aggregationType"] = $aggregationType->value;
        }
        // Request the spreadsheet data
        $response = $this->performRequest(
            method: "POST",
            endpoint: "/webmasters/v3/sites/" . $siteUrl . "/searchAnalytics/query",
            query: $query,
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
     * @param array|null $dimensionFilterGroups
     * @param GroupType $dimensionFilterGroupsGroupType
     * @param array|null $dimensionFilterGroupsFilters
     * @param Dimension $dimensionFilterGroupsFiltersDimension
     * @param Device|SearchAppearance|string $dimensionFilterGroupsFiltersDimensionValue
     * @param Operator $dimensionFilterGroupsFiltersDimensionOperator
     * @param AggregationType $aggregationType
     * @return array
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
        GroupType $dimensionFilterGroupsGroupType = GroupType::AND,
        ?array $dimensionFilterGroupsFilters = null,
        Dimension $dimensionFilterGroupsFiltersDimension = Dimension::COUNTRY,
        Device|SearchAppearance|string $dimensionFilterGroupsFiltersDimensionValue = Device::MOBILE,
        Operator $dimensionFilterGroupsFiltersDimensionOperator = Operator::EQUALS,
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
            "dimensionFilterGroupsGroupType" => $dimensionFilterGroupsGroupType,
            "dimensionFilterGroupsFilters" => $dimensionFilterGroupsFilters,
            "dimensionFilterGroupsFiltersDimension" => $dimensionFilterGroupsFiltersDimension,
            "dimensionFilterGroupsFiltersDimensionValue" => $dimensionFilterGroupsFiltersDimensionValue,
            "dimensionFilterGroupsFiltersDimensionOperator" => $dimensionFilterGroupsFiltersDimensionOperator,
            "aggregationType" => $aggregationType,
        ];
        do {
            // Request the spreadsheet data
            $response = $this->getQueryResults(...$params);
            $rows = array_merge($rows, $response["rows"] ?? []);
        } while (count($response["rows"]) == $rowLimit);
        // Return response
        return ['rows' => $rows];
    }
}
