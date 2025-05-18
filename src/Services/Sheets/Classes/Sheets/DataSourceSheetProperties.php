<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Sheets;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\DataExecutionStatus;

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
