<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Cells;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\ColorStyle;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Cells\Style;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/cells#border
 */
class Border implements Jsonable
{
    public ColorStyle|array $colorStyle;
    public Style|string $style;
    
    public function __construct(
        ColorStyle|array $colorStyle,
        Style|string $style = Style::SOLID,
        mixed $width = null,
        mixed $color = null,
    ) {
        $this->colorStyle = $this->arrayToObject(class: ColorStyle::class, var: $colorStyle);
        $this->style = $this->stringToEnum(enum: Style::class, var: $style);
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
