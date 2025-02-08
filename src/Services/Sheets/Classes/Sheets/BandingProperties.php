<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Sheets;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\ColorStyle;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/sheets#bandingproperties
 */
class BandingProperties implements Jsonable
{
    public ColorStyle|array $firstBandColorStyle;
    public ColorStyle|array $secondBandColorStyle;
    public ColorStyle|array|null $headerColorStyle;
    public ColorStyle|array|null $footerColorStyle;
    
    public function __construct(
        ColorStyle|array $firstBandColorStyle,
        ColorStyle|array $secondBandColorStyle,
        ColorStyle|array|null $headerColorStyle = null,
        ColorStyle|array|null $footerColorStyle = null,
    ) {
        $this->firstBandColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $firstBandColorStyle);
        $this->secondBandColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $secondBandColorStyle);
        $this->headerColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $headerColorStyle);
        $this->footerColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $footerColorStyle);
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
