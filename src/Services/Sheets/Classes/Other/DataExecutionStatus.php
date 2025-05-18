<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Other\DataExecutionErrorCode;
use Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Other\DataExecutionState;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#dataexecutionstatus
 */
class DataExecutionStatus implements Jsonable
{
    public string $errorMessage;
    public string $lastRefreshTime;
    public DataExecutionState|string $state;
    public DataExecutionErrorCode|string $errorCode;
    
    public function __construct(
        string $errorMessage,
        string $lastRefreshTime,
        DataExecutionState|string $state = DataExecutionState::DATA_EXECUTION_STATE_UNSPECIFIED,
        DataExecutionErrorCode|string $errorCode = DataExecutionErrorCode::DATA_EXECUTION_ERROR_CODE_UNSPECIFIED,
    ) {
        $this->errorMessage = $errorMessage;
        $this->lastRefreshTime = $lastRefreshTime;
        $this->state = $this->stringToEnum(enum: DataExecutionState::class, var: $state);
        $this->errorCode = $this->stringToEnum(enum: DataExecutionErrorCode::class, var: $errorCode);
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
