<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\Waterfall;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts\ChartData;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\ColorStyle;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#waterfallchartcolumnstyle
 */
class WaterfallChartColumnStyle implements Jsonable
{
    public string $label;
    public ColorStyle|array $colorStyle;
    
    public function __construct(
        string $label,
        ColorStyle|array $colorStyle,
    ) {
        $this->label = $label;
        $this->colorStyle = $this->arrayToObject(class: ColorStyle::class, var: $colorStyle);
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
