<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Other;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#dataexecutionerrorcode
 */
enum DataExecutionErrorCode: string
{
    case DATA_EXECUTION_ERROR_CODE_UNSPECIFIED = 'DATA_EXECUTION_ERROR_CODE_UNSPECIFIED';
    case TIMED_OUT = 'TIMED_OUT';
    case TOO_MANY_ROWS = 'TOO_MANY_ROWS';
    case TOO_MANY_COLUMNS = 'TOO_MANY_COLUMNS';
    case TOO_MANY_CELLS = 'TOO_MANY_CELLS';
    case ENGINE = 'ENGINE';
    case PARAMETER_INVALID = 'PARAMETER_INVALID';
    case UNSUPPORTED_DATA_TYPE = 'UNSUPPORTED_DATA_TYPE';
    case DUPLICATE_COLUMN_NAMES = 'DUPLICATE_COLUMN_NAMES';
    case INTERRUPTED = 'INTERRUPTED';
    case CONCURRENT_QUERY = 'CONCURRENT_QUERY';
    case OTHER = 'OTHER';
    case TOO_MANY_CHARS_PER_CELL = 'TOO_MANY_CHARS_PER_CELL';
    case DATA_NOT_FOUND = 'DATA_NOT_FOUND';
    case PERMISSION_DENIED = 'PERMISSION_DENIED';
    case MISSING_COLUMN_ALIAS = 'MISSING_COLUMN_ALIAS';
    case OBJECT_NOT_FOUND = 'OBJECT_NOT_FOUND';
    case OBJECT_IN_ERROR_STATE = 'OBJECT_IN_ERROR_STATE';
    case OBJECT_SPEC_INVALID = 'OBJECT_SPEC_INVALID';
}
