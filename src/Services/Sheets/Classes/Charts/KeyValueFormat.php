<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Charts;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\TextFormat;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\TextPosition;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#keyvalueformat
 */
class KeyValueFormat implements Jsonable
{
    public TextFormat|array $textFormat;
    public TextPosition|array $position;
    
    public function __construct(
        TextFormat|array $textFormat,
        TextPosition|array $position,
    ) {
        $this->textFormat = $this->arrayToObject(class: TextFormat::class, var: $textFormat);
        $this->position = $this->arrayToObject(class: TextPosition::class, var: $position);
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
