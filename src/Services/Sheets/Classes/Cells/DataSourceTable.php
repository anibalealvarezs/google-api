<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Cells;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Other\DataExecutionStatus;
use Chmw\GoogleApi\Services\Sheets\Enums\Cells\DataSourceTableColumnSelectionType;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/cells#datasourcetable
 */
class DataSourceTable implements Jsonable
{
    public string $dataSourceId;
    public array $columns;
    public array $filterSpecs;
    public array $sortSpecs;
    public int $rowLimit;
    public DataExecutionStatus|array $dataExecutionStatus;
    public DataSourceTableColumnSelectionType|string $columnSelectionType;
    
    public function __construct(
        string $dataSourceId,
        array $columns,
        array $filterSpecs,
        array $sortSpecs,
        int $rowLimit,
        DataExecutionStatus|array $dataExecutionStatus,
        DataSourceTableColumnSelectionType|string $columnSelectionType = DataSourceTableColumnSelectionType::SYNC_ALL,
    ) {
        $this->dataSourceId = $dataSourceId;
        $this->columns = $columns;
        $this->filterSpecs = $filterSpecs;
        $this->sortSpecs = $sortSpecs;
        $this->rowLimit = $rowLimit;
        $this->dataExecutionStatus = $this->arrayToObject(class: DataExecutionStatus::class, var: $dataExecutionStatus);
        $this->columnSelectionType = $this->stringToEnum(enum: DataSourceTableColumnSelectionType::class, var: $columnSelectionType);
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

    public function stringToEnum(string $enum, mixed $var): mixed
    {
        if (is_string($var)) {
            return $enum::from($var);
        }
        return $var;
    }
}
