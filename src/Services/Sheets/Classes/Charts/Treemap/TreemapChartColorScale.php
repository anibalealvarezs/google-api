<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\Treemap;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\ColorStyle;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#treemapchartcolorscale
 */
class TreemapChartColorScale implements Jsonable
{
    public ColorStyle|array|null $minValueColorStyle;
    public ColorStyle|array|null $midValueColorStyle;
    public ColorStyle|array|null $maxValueColorStyle;
    public ColorStyle|array|null $noDataColorStyle;
    
    public function __construct(
        ColorStyle|array|null $minValueColorStyle = null, // Default: #dc3912
        ColorStyle|array|null $midValueColorStyle = null, // Default: #efe6dc
        ColorStyle|array|null $maxValueColorStyle = null, // Default: #109618
        ColorStyle|array|null $noDataColorStyle = null, // Default: #000000
    ) {
        $this->minValueColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $minValueColorStyle);
        $this->midValueColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $midValueColorStyle);
        $this->maxValueColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $maxValueColorStyle);
        $this->noDataColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $noDataColorStyle);
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
