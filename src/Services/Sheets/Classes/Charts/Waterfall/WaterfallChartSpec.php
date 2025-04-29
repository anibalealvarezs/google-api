<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\Waterfall;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\DataLabel;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\LineStyle;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\Waterfall\WaterfallChartStackedType;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#waterfallchartspec
 * @param WaterfallChartDomain|array $domain
 * @param WaterfallChartSeries[] $series
 * @param LineStyle|array $connectorLineStyle
 * @param DataLabel|array $totalDataLabel
 * @param WaterfallChartStackedType|string $stackedType
 * @param bool $firstValueIsTotal
 * @param bool $hideConnectorLines
 * @return WaterfallChartSpec
 */
class WaterfallChartSpec implements Jsonable
{
    public WaterfallChartDomain|array $domain;
    public array $series;
    public LineStyle|array $connectorLineStyle;
    public DataLabel|array $totalDataLabel;
    public WaterfallChartStackedType|string $stackedType;
    public bool $firstValueIsTotal;
    public bool $hideConnectorLines;
    
    public function __construct(
        WaterfallChartDomain|array $domain,
        array $series,
        LineStyle|array $connectorLineStyle,
        DataLabel|array $totalDataLabel,
        WaterfallChartStackedType|string $stackedType = WaterfallChartStackedType::STACKED,
        bool $firstValueIsTotal = false,
        bool $hideConnectorLines = false,
    ) {
        $formattedSeries = [];
        foreach ($series as $singleSeries) {
            if (!($singleSeries instanceof WaterfallChartSeries)) {
                $formattedSeries[] = new WaterfallChartSeries(...$singleSeries);
            } else {
                $formattedSeries[] = $singleSeries;
            }
        }
        $this->domain = $this->arrayToObject(class: WaterfallChartDomain::class, var: $domain);
        $this->series = $formattedSeries;
        $this->connectorLineStyle = $this->arrayToObject(class: LineStyle::class, var: $connectorLineStyle);
        $this->totalDataLabel = $this->arrayToObject(class: DataLabel::class, var: $totalDataLabel);
        $this->stackedType = $this->stringToEnum(enum: WaterfallChartStackedType::class, var: $stackedType);
        $this->firstValueIsTotal = $firstValueIsTotal;
        $this->hideConnectorLines = $hideConnectorLines;
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
