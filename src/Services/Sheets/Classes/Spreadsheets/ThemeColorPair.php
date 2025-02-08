<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Spreadsheets;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\ColorStyle;
use Chmw\GoogleApi\Services\Sheets\Enums\Other\ThemeColorType;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets#themecolorpair
 */
class ThemeColorPair implements Jsonable
{
    public ThemeColorType|string $colorType;
    public ColorStyle|array $color;
    
    public function __construct(
        ThemeColorType|string $colorType,
        ColorStyle|array $color,
    ) {
        $this->colorType = $this->stringToEnum(enum: ThemeColorType::class, var: $colorType);
        $this->color = $this->arrayToObject(class: ColorStyle::class, var: $color);
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
