<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\PivotTables;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\ExtendedValue;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/pivot-tables#manualrulegroup
 */
class ManualRuleGroup implements Jsonable
{
    public ExtendedValue|array $groupName;
    public array $items;
    
    public function __construct(
        ExtendedValue|array $groupName,
        array $items,
    ) {
        $this->groupName = $this->arrayToObject(class: ExtendedValue::class, var: $groupName);
        $this->items = $items;
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
