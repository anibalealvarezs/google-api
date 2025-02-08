<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Enums\Dimension;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/DimensionRange
 */
class DimensionRange implements Jsonable
{
    public int $sheetId;
    public ?int $startIndex;
    public ?int $endIndex;
    public Dimension|string $dimension;
    
    public function __construct(
        int $sheetId,
        ?int $startIndex = null,
        ?int $endIndex = null,
        Dimension|string $dimension = Dimension::ROWS,
    ) {
        $this->sheetId = $sheetId;
        $this->startIndex = $startIndex;
        $this->endIndex = $endIndex;
        $this->dimension = $this->stringToEnum(enum: Dimension::class, var: $dimension);
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
