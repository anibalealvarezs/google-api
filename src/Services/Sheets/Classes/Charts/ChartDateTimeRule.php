<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\ChartDateTimeRuleType;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#chartdatetimerule
 */
class ChartDateTimeRule implements Jsonable
{
    public ChartDateTimeRuleType|string $type;
    
    public function __construct(
        ChartDateTimeRuleType|string $type = ChartDateTimeRuleType::DAY_MONTH
    ) {
        $this->type = $this->stringToEnum(enum: ChartDateTimeRuleType::class, var: $type);
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
