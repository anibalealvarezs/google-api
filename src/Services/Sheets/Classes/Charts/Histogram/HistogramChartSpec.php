<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Charts\Histogram;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Enums\Charts\Histogram\HistogramChartLegendPosition;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#histogramchartspec
 * @param HistogramSeries[] $series
 * @param float $bucketSize
 * @param float $outlierPercentile
 * @param HistogramChartLegendPosition|string $legendPosition
 * @param bool $showItemDividers
 * @return HistogramChartSpec
 */
class HistogramChartSpec implements Jsonable
{
    public array $series;
    public float $bucketSize;
    public float $outlierPercentile;
    public HistogramChartLegendPosition|string $legendPosition;
    public bool $showItemDividers;
    
    public function __construct(
        array $series,
        float $bucketSize,
        float $outlierPercentile,
        HistogramChartLegendPosition|string $legendPosition = HistogramChartLegendPosition::BOTTOM_LEGEND,
        bool $showItemDividers = false,
    ) {
        $formattedSeries = [];
        foreach ($series as $singleSeries) {
            if (!($singleSeries instanceof HistogramSeries)) {
                $formattedSeries[] = new HistogramSeries(...$singleSeries);
            } else {
                $formattedSeries[] = $singleSeries;
            }
        }
        $this->series = $formattedSeries;
        $this->bucketSize = $bucketSize;
        $this->outlierPercentile = $outlierPercentile;
        $this->legendPosition = $this->stringToEnum(enum: HistogramChartLegendPosition::class, var: $legendPosition);
        $this->showItemDividers = $showItemDividers;
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
}
