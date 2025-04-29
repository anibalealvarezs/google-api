<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\DataSourceColumnReference;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\ChartAggregateType;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#chartdata
 */
class ChartData implements Jsonable
{
    public ChartGroupRule|array|null $groupRule;
    public ChartAggregateType|string|null $aggregateType;
    public ChartSourceRange|array|null $sourceRange;
    public DataSourceColumnReference|array|null $columnReference;
    
    public function __construct(
        ChartGroupRule|array|null $groupRule = null,
        ChartAggregateType|string|null $aggregateType = ChartAggregateType::AVERAGE,
        ChartSourceRange|array|null $sourceRange = null,
        DataSourceColumnReference|array|null $columnReference = null,
    ) {
        $this->groupRule = $this->arrayToObject(class: ChartGroupRule::class, var: $groupRule);
        $this->aggregateType = $this->stringToEnum(enum: ChartAggregateType::class, var: $aggregateType);
        $this->sourceRange = $this->arrayToObject(class: ChartSourceRange::class, var: $sourceRange);
        $this->columnReference = $this->arrayToObject(class: DataSourceColumnReference::class, var: $columnReference);

        $this->keepOneOfKind([
            'sourceRange',
            'columnReference'
        ]);
    }

    public function keepOneOfKind(array $properties): void
    {
        if ($key = Helpers::getFirstNotNullPropertyFrom($this, $properties)) {
            Helpers::nullifyOtherProperties($this, $key);
        }
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }

    public function arrayToObject(string $class, mixed $var): mixed
    {
        if (is_array($var)) {
            return new $class(...$var);
        }
        return $var;
    }

    public function stringToEnum(string $enum, mixed $var): mixed
    {
        if (is_string($var)) {
            return $enum::from($var);
        }
        return $var;
    }
}
