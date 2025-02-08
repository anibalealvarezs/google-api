<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Charts;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Enums\Charts\LineDashType;

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
