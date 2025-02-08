<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Cells;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\DataExecutionStatus;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/cells#datasourceformula
 */
class DataSourceFormula implements Jsonable
{
    public string $dataSourceId;
    public readonly DataExecutionStatus|array $dataExecutionStatus;
    
    public function __construct(
        string $dataSourceId,
        DataExecutionStatus|array $dataExecutionStatus,
    ) {
        $this->dataSourceId = $dataSourceId;
        $this->dataExecutionStatus = $this->arrayToObject(class: DataExecutionStatus::class, var: $dataExecutionStatus);
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
