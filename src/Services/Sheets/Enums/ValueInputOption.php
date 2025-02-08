<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/ValueInputOption
 */
enum ValueInputOption: string
{
    case INPUT_VALUE_OPTION_UNSPECIFIED = 'INPUT_VALUE_OPTION_UNSPECIFIED';
    case RAW = 'RAW';
    case USER_ENTERED = 'USER_ENTERED';
}
