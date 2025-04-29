<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\PivotTables;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\PivotTables\DateTimeRuleType;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/pivot-tables#datetimerule
 */
class DateTimeRule implements Jsonable
{
    public DateTimeRuleType|string $type;
    
    public function __construct(
        DateTimeRuleType|string $type = DateTimeRuleType::DAY_OF_MONTH,
    ) {
        $this->type = $this->stringToEnum(enum: DateTimeRuleType::class, var: $type);
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
