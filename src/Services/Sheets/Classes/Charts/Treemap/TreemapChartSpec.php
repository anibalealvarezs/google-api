<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Charts\Treemap;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Charts\ChartData;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\ColorStyle;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\TextFormat;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#treemapchartspec
 */
class TreemapChartSpec implements Jsonable
{
    public ChartData|array $labels;
    public ChartData|array $parentLabels;
    public ChartData|array $sizeData;
    public ChartData|array $colorData;
    public TextFormat|array $textFormat;
    public ColorStyle|array $headerColorStyle;
    public TreemapChartColorScale|array $colorScale;
    public ?int $levels;
    public ?int $hintedLevels;
    public ?float $minValue;
    public ?float $maxValue;
    public float $hideTooltips;
    
    public function __construct(
        ChartData|array $labels,
        ChartData|array $parentLabels,
        ChartData|array $sizeData,
        ChartData|array $colorData,
        TextFormat|array $textFormat,
        ColorStyle|array $headerColorStyle,
        TreemapChartColorScale|array $colorScale,
        ?int $levels = null,
        ?int $hintedLevels = null,
        ?float $minValue = null,
        ?float $maxValue = null,
        float $hideTooltips = false,
    ) {
        $this->labels = $this->arrayToObject(class: ChartData::class, var: $labels);
        $this->parentLabels = $this->arrayToObject(class: ChartData::class, var: $parentLabels);
        $this->sizeData = $this->arrayToObject(class: ChartData::class, var: $sizeData);
        $this->colorData = $this->arrayToObject(class: ChartData::class, var: $colorData);
        $this->textFormat = $this->arrayToObject(class: TextFormat::class, var: $textFormat);
        $this->headerColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $headerColorStyle);
        $this->colorScale = $this->arrayToObject(class: TreemapChartColorScale::class, var: $colorScale);
        $this->levels = $levels;
        $this->hintedLevels = $hintedLevels;
        $this->minValue = $minValue;
        $this->maxValue = $maxValue;
        $this->hideTooltips = $hideTooltips;
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
}
