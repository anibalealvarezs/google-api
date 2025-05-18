<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\Basic;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\DataLabel;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\Basic\BasicChartCompareMode;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\Basic\BasicChartLegendPosition;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\Basic\BasicChartStackedType;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\Basic\BasicChartType;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#basicchartspec
 * @param BasicChartAxis[] $axis
 * @param BasicChartDomain[] $domains
 * @param BasicChartSeries[] $series
 * @param BasicChartType|string $chartType
 * @param DataLabel|array|null $totalDataLabel
 * @param BasicChartLegendPosition|string|null $legendPosition
 * @param BasicChartStackedType|string|null $stackedType
 * @param BasicChartCompareMode|string|null $compareMode
 * @param int|null $headerCount
 * @param bool $threeDimensional
 * @param bool $interpolateNulls
 * @param bool $lineSmoothing
 * @return BasicChartSpec
 */
class BasicChartSpec implements Jsonable
{
    public array $axis;
    public array $domains;
    public array $series;
    public BasicChartType|string $chartType;
    public DataLabel|array|null $totalDataLabel;
    public BasicChartLegendPosition|string|null $legendPosition;
    public BasicChartStackedType|string|null $stackedType;
    public BasicChartCompareMode|string|null $compareMode;
    public ?int $headerCount;
    public bool $threeDimensional;
    public bool $interpolateNulls;
    public bool $lineSmoothing;
    
    public function __construct(
        array $axis,
        array $domains,
        array $series,
        BasicChartType|string $chartType = BasicChartType::LINE,
        DataLabel|array|null $totalDataLabel = null,
        BasicChartLegendPosition|string|null $legendPosition = BasicChartLegendPosition::BOTTOM_LEGEND,
        BasicChartStackedType|string|null $stackedType = BasicChartStackedType::NOT_STACKED,
        BasicChartCompareMode|string|null $compareMode = BasicChartCompareMode::DATUM,
        ?int $headerCount = null,
        bool $threeDimensional = false,
        bool $interpolateNulls = false,
        bool $lineSmoothing = true
    ) {
        $formattedAxis = [];
        if ($axis) {
            foreach ($axis as $singleAxis) {
                if (!($singleAxis instanceof BasicChartAxis)) {
                    $formattedAxis[] = new BasicChartAxis(...$singleAxis);
                } else {
                    $formattedAxis[] = $singleAxis;
                }
            }
        }
        $formattedDomains = [];
        if ($domains) {
            foreach ($domains as $domain) {
                if (!($domain instanceof BasicChartDomain)) {
                    $formattedDomains[] = new BasicChartDomain(...$domain);
                } else {
                    $formattedDomains[] = $domain;
                }
            }
        }
        $formattedSeries = [];
        if ($series) {
            foreach ($series as $singleSeries) {
                if (!($singleSeries instanceof BasicChartSeries)) {
                    $formattedSeries[] = new BasicChartSeries(...$singleSeries);
                } else {
                    $formattedSeries[] = $singleSeries;
                }
            }
        }
        $this->axis = $formattedAxis;
        $this->domains = $formattedDomains;
        $this->series = $formattedSeries;
        $this->chartType = $this->stringToEnum(enum: BasicChartType::class, var: $chartType);
        $this->totalDataLabel = $this->arrayToObject(class: DataLabel::class, var: $totalDataLabel);
        $this->legendPosition = $this->stringToEnum(enum: BasicChartLegendPosition::class, var: $legendPosition);
        $this->stackedType = $this->stringToEnum(enum: BasicChartStackedType::class, var: $stackedType);
        $this->compareMode = $this->stringToEnum(enum: BasicChartCompareMode::class, var: $compareMode);
        $this->headerCount = $headerCount;
        $this->threeDimensional = $threeDimensional;
        $this->interpolateNulls = $interpolateNulls;
        $this->lineSmoothing = $lineSmoothing;
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
