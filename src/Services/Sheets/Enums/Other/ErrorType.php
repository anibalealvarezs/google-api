<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Other;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#errortype
 */
enum ErrorType: string
{
    case ERROR_TYPE_UNSPECIFIED = 'ERROR_TYPE_UNSPECIFIED';
    case ERROR = 'ERROR';
    case NULL_VALUE = 'NULL_VALUE';
    case DIVIDE_BY_ZERO = 'DIVIDE_BY_ZERO';
    case VALUE = 'VALUE';
    case REF = 'REF';
    case NAME = 'NAME';
    case NUM = 'NUM';
    case N_A = 'N_A';
    case LOADING = 'LOADING';
}
