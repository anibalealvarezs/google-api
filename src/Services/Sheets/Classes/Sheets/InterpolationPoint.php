<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Sheets;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\ColorStyle;
use Chmw\GoogleApi\Services\Sheets\Enums\Sheets\InterpolationPointType;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/sheets#interpolationpoint
 */
class InterpolationPoint implements Jsonable
{
    public ColorStyle|array $colorStyle;
    public InterpolationPointType|string $type;
    public ?string $value;
    
    public function __construct(
        ColorStyle|array $colorStyle,
        InterpolationPointType|string $type = InterpolationPointType::MIN,
        ?string $value = null,
    ) {
        $this->colorStyle = $this->arrayToObject(class: ColorStyle::class, var: $colorStyle);
        $this->type = $this->stringToEnum(enum: InterpolationPointType::class, var: $type);
        $this->value = $value;
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
