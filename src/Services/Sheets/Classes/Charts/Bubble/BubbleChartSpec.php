<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Charts\Bubble;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Charts\ChartData;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\ColorStyle;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\TextFormat;
use Chmw\GoogleApi\Services\Sheets\Enums\Charts\Bubble\BubbleChartLegendPosition;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#bubblechartspec
 */
class BubbleChartSpec implements Jsonable
{
    public ChartData|array $bubbleLabels;
    public ChartData|array $domain;
    public ChartData|array $series;
    public ChartData|array $groupIds;
    public ChartData|array $bubbleSizes;
    public ColorStyle|array $bubbleBorderColorStyle;
    public TextFormat|array $bubbleTextStyle;
    public BubbleChartLegendPosition|string $legendPosition;
    public float $bubbleOpacity;
    public ?int $bubbleMaxRadiusSize;
    public ?int $bubbleMinRadiusSize;
    
    public function __construct(
        ChartData|array $bubbleLabels,
        ChartData|array $domain,
        ChartData|array $series,
        ChartData|array $groupIds,
        ChartData|array $bubbleSizes,
        ColorStyle|array $bubbleBorderColorStyle,
        TextFormat|array $bubbleTextStyle,
        BubbleChartLegendPosition|string $legendPosition = BubbleChartLegendPosition::BOTTOM_LEGEND,
        float $bubbleOpacity = 1.0,
        ?int $bubbleMaxRadiusSize = null,
        ?int $bubbleMinRadiusSize = null,
    ) {
        $this->bubbleLabels = $this->arrayToObject(class: ChartData::class, var: $bubbleLabels);
        $this->domain = $this->arrayToObject(class: ChartData::class, var: $domain);
        $this->series = $this->arrayToObject(class: ChartData::class, var: $series);
        $this->groupIds = $this->arrayToObject(class: ChartData::class, var: $groupIds);
        $this->bubbleSizes = $this->arrayToObject(class: ChartData::class, var: $bubbleSizes);
        $this->bubbleBorderColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $bubbleBorderColorStyle);
        $this->bubbleTextStyle = $this->arrayToObject(class: TextFormat::class, var: $bubbleTextStyle);
        $this->legendPosition = $this->stringToEnum(enum: BubbleChartLegendPosition::class, var: $legendPosition);
        $this->bubbleOpacity = $bubbleOpacity;
        $this->bubbleMaxRadiusSize = $bubbleMaxRadiusSize;
        $this->bubbleMinRadiusSize = $bubbleMinRadiusSize;
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
