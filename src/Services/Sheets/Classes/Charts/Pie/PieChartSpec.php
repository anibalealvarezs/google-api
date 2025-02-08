<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Charts\Pie;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Charts\ChartData;
use Chmw\GoogleApi\Services\Sheets\Enums\Charts\Pie\PieChartLegendPosition;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#piechartspec
 */
class PieChartSpec implements Jsonable
{
    public ChartData|array $domain;
    public ChartData|array $series;
    public float $pieHole;
    public PieChartLegendPosition|string $legendPosition;
    public bool $threeDimensional;
    
    public function __construct(
        ChartData|array $domain,
        ChartData|array $series,
        float $pieHole,
        PieChartLegendPosition|string $legendPosition = PieChartLegendPosition::BOTTOM_LEGEND,
        bool $threeDimensional = false,
    ) {
        $this->domain = $this->arrayToObject(class: ChartData::class, var: $domain);
        $this->series = $this->arrayToObject(class: ChartData::class, var: $series);
        $this->pieHole = $pieHole;
        $this->legendPosition = $this->stringToEnum(enum: PieChartLegendPosition::class, var: $legendPosition);
        $this->threeDimensional = $threeDimensional;
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
