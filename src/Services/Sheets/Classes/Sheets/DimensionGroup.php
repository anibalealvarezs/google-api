<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Sheets;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\DimensionRange;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/sheets#dimensiongroup
 */
class DimensionGroup implements Jsonable
{
    public DimensionRange|array $range;
    public int $depth;
    public bool $collapsed;
    
    public function __construct(
        DimensionRange|array $range,
        int $depth,
        bool $collapsed = false,
    ) {
        $this->range = $this->arrayToObject(class: DimensionRange::class, var: $range);
        $this->depth = $depth;
        $this->collapsed = $collapsed;
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
