<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Other;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#dataexecutionstate
 */
enum DataExecutionState: string
{
    case DATA_EXECUTION_STATE_UNSPECIFIED = 'DATA_EXECUTION_STATE_UNSPECIFIED';
    case NOT_STARTED = 'NOT_STARTED';
    case RUNNING = 'RUNNING';
    case SUCCEEDED = 'SUCCEEDED';
    case FAILED = 'FAILED';
}
