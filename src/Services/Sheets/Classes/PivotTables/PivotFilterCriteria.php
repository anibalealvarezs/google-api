<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\PivotTables;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\BooleanCondition;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/pivot-tables#pivotfiltercriteria
 */
class PivotFilterCriteria implements Jsonable
{
    public array $visibleValues;
    public BooleanCondition|array|null $condition;
    public bool $visibleByDefault;
    
    public function __construct(
        array $visibleValues = [],
        BooleanCondition|array|null $condition = null,
        bool $visibleByDefault = true,
    ) {
        $this->visibleValues = $visibleValues;
        $this->condition = $this->arrayToObject(class: BooleanCondition::class, var: $condition);
        $this->visibleByDefault = $visibleByDefault;
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
