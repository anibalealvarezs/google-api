<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Charts\Scorecard;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Charts\BaselineValueFormat;
use Chmw\GoogleApi\Services\Sheets\Classes\Charts\ChartCustomNumberFormatOptions;
use Chmw\GoogleApi\Services\Sheets\Classes\Charts\ChartData;
use Chmw\GoogleApi\Services\Sheets\Classes\Charts\KeyValueFormat;
use Chmw\GoogleApi\Services\Sheets\Enums\Charts\ChartAggregateType;
use Chmw\GoogleApi\Services\Sheets\Enums\Charts\ChartNumberFormatSource;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#scorecardchartspec
 */
class ScorecardChartSpec implements Jsonable
{
    public ChartData|array $keyValueData;
    public KeyValueFormat|array $keyValueFormat;
    public ?float $scaleFactor;
    public ChartData|array|null $baselineValueData;
    public ChartAggregateType|string $aggregateType;
    public BaselineValueFormat|array|null $baselineValueFormat;
    public ChartNumberFormatSource|string $numberFormatSource;
    public ChartCustomNumberFormatOptions|array|null $customFormatOptions;
    
    public function __construct(
        ChartData|array $keyValueData,
        KeyValueFormat|array $keyValueFormat,
        ?float $scaleFactor = null,
        ChartData|array|null $baselineValueData = null,
        ChartAggregateType|string $aggregateType = ChartAggregateType::AVERAGE,
        BaselineValueFormat|array|null $baselineValueFormat = null,
        ChartNumberFormatSource|string $numberFormatSource = ChartNumberFormatSource::FROM_DATA,
        ChartCustomNumberFormatOptions|array|null $customFormatOptions = null,
    ) {
        $this->keyValueData = $this->arrayToObject(class: ChartData::class, var: $keyValueData);
        $this->keyValueFormat = $this->arrayToObject(class: KeyValueFormat::class, var: $keyValueFormat);
        $this->scaleFactor = $scaleFactor;
        $this->baselineValueData = $this->arrayToObject(class: ChartData::class, var: $baselineValueData);
        $this->aggregateType = $this->stringToEnum(enum: ChartAggregateType::class, var: $aggregateType);
        $this->baselineValueFormat = $this->arrayToObject(class: BaselineValueFormat::class, var: $baselineValueFormat);
        $this->numberFormatSource = $this->stringToEnum(enum: ChartNumberFormatSource::class, var: $numberFormatSource);
        $this->customFormatOptions = $this->arrayToObject(class: ChartCustomNumberFormatOptions::class, var: $customFormatOptions);
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
