<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\LineDashType;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#linestyle
 */
class LineStyle implements Jsonable
{
    public int $width;
    public LineDashType|string $type;
    
    public function __construct(
        int $width,
        LineDashType|string $type = LineDashType::SOLID,
    ) {
        $this->width = $width;
        $this->type = $this->stringToEnum(enum: LineDashType::class, var: $type);
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
