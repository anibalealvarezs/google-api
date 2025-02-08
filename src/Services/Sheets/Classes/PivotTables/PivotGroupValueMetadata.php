<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\PivotTables;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\ExtendedValue;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/pivot-tables#pivotgroupvaluemetadata
 */
class PivotGroupValueMetadata implements Jsonable
{
    public ExtendedValue|array $value;
    public bool $collapsed;
    
    public function __construct(
        ExtendedValue|array $value,
        bool $collapsed = false,
    ) {
        $this->value = $this->arrayToObject(class: ExtendedValue::class, var: $value);
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
