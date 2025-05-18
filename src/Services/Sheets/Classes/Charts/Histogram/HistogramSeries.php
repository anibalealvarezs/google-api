<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\Histogram;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\ChartData;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\ColorStyle;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#histogramseries
 */
class HistogramSeries implements Jsonable
{
    public ColorStyle|array $barColorStyle;
    public ChartData|array $data;
    
    public function __construct(
        ColorStyle|array $barColorStyle,
        ChartData|array $data
    ) {
        $this->barColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $barColorStyle);
        $this->data = $this->arrayToObject(class: ChartData::class, var: $data);
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
