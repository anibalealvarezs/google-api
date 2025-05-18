<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Cells;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Cells\NumberFormatType;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/cells#numberformat
 */
class NumberFormat implements Jsonable
{
    public NumberFormatType|string $type;
    public ?string $pattern;
    
    public function __construct(
        NumberFormatType|string $type = NumberFormatType::NUMBER,
        ?string $pattern = null,
    ) {
        $this->type = $this->stringToEnum(enum: NumberFormatType::class, var: $type);
        $this->pattern = $pattern;
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
