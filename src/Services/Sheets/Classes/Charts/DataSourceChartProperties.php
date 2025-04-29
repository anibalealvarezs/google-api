<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Charts;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Other\DataExecutionState;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#datasourcechartproperties
 */
class DataSourceChartProperties implements Jsonable
{
    public string $dataSourceId;
    public DataExecutionState|string|null $dataExecutionStatus;
    
    public function __construct(
        string $dataSourceId,
        DataExecutionState|string|null $dataExecutionStatus = null,
    ) {
        $this->dataSourceId = $dataSourceId;
        $this->dataExecutionStatus = $this->stringToEnum(enum: DataExecutionState::class, var: $dataExecutionStatus);
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
