<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Charts\Basic;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Charts\PointStyle;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\ColorStyle;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#basicseriesdatapointstyleoverride
 */
class BasicSeriesDataPointStyleOverride implements Jsonable
{
    public ColorStyle|array $colorStyle;
    public PointStyle|array|null $pointStyle;
    public int $index;
    
    public function __construct(
        ColorStyle|array $colorStyle,
        PointStyle|array|null $pointStyle = null,
        int $index = 0,
    ) {
        $this->colorStyle = $this->arrayToObject(class: ColorStyle::class, var: $colorStyle);
        $this->pointStyle = $this->arrayToObject(class: PointStyle::class, var: $pointStyle);
        $this->index = $index;
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
