<?php

namespace Anibalealvarezs\GoogleApi\Services\SearchConsole\Classes;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\SearchConsole\Enums\GroupType;

/**
 * @see https://developers.google.com/webmaster-tools/v1/searchanalytics/query#dimensionFilterGroups
 * @param DimensionFilter[] $filters
 */
class DimensionFilterGroup implements Jsonable
{
    public ?array $filters;
    public GroupType|string $groupType;

    public function __construct(
        ?array $filters = null,
        GroupType|string $groupType = GroupType::AND,
    ) {
        $formattedFilters = [];
        if ($filters) {
            foreach ($filters as $filter) {
                if (!($filter instanceof DimensionFilter)) {
                    $formattedFilters[] = new DimensionFilter(...$filter);
                } else {
                    $formattedFilters[] = $filter;
                }
            }
        }
        $this->filters = $formattedFilters;
        $this->groupType = $this->stringToEnum(enum: GroupType::class, var: $groupType);
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }

    public function stringToEnum(string $enum, mixed $var): mixed
    {
        if (is_string($var)) {
            return $enum::from($var);
        }
        return $var;
    }

    public function arrayToObject(string $class, mixed $var): mixed
    {
        if (is_array($var)) {
            return new $class(...$var);
        }
        return $var;
    }
}