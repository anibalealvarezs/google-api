<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Pages\Other\RgbColor;
use Chmw\GoogleApi\Services\Slides\Enums\Pages\Other\ThemeColorType;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages#themecolorpair
 */
class ThemeColorPair implements Jsonable
{
    public ThemeColorType|string $type;
    public RgbColor|array $color;
    
    public function __construct(
        ThemeColorType|string $type,
        RgbColor|array $color
    ) {
        $this->type = $this->stringToEnum(enum: ThemeColorType::class, var: $type);
        $this->color = $this->arrayToObject(class: RgbColor::class, var: $color);
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
