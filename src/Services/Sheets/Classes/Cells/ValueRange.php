<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Cells;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Enums\Dimension;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets.values#ValueRange
 */
class ValueRange implements Jsonable
{
    public string $range;
    public array $values;
    public Dimension|string $majorDimension;
    
    public function __construct(
        string $range,
        array $values = [],
        Dimension|string $majorDimension = Dimension::ROWS,
    ) {
        $this->range = $range;
        $this->values = $values;
        $this->majorDimension = $this->stringToEnum(enum: Dimension::class, var: $majorDimension);
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }

    public function stringToEnum(string $enum, mixed $var): mixed
    {
        if (is_string($var)) {
            return $enum::from($var);
        }
        return $var;
    }
}
