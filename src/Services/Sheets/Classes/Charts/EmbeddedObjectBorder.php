<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Charts;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\ColorStyle;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#embeddedobjectborder
 */
class EmbeddedObjectBorder implements Jsonable
{
    public ColorStyle|array $colorStyle;
    
    public function __construct(
        ColorStyle|array $colorStyle
    ) {
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
