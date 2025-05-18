<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Sheets;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Cells\CellFormat;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\BooleanCondition;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/sheets#booleanrule
 */
class BooleanRule implements Jsonable
{
    public BooleanCondition|array $condition;
    public CellFormat|array $format;
    
    public function __construct(
        BooleanCondition|array $condition,
        CellFormat|array $format,
    ) {
        $this->condition = $this->arrayToObject(class: BooleanCondition::class, var: $condition);
        $this->format = $this->arrayToObject(class: CellFormat::class, var: $format);
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
