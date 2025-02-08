<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Sheets;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\DataExecutionStatus;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/sheets#datasourcesheetproperties
 */
class DataSourceSheetProperties implements Jsonable
{
    public string $dataSourceId;
    public array $columns;
    public DataExecutionStatus|array $dataExecutionStatus;
    
    public function __construct(
        string $dataSourceId,
        array $columns,
        DataExecutionStatus|array $dataExecutionStatus,
    ) {
        $this->dataSourceId = $dataSourceId;
        $this->columns = $columns;
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
