<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Spreadsheets;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets#recalculationinterval
 */
enum RecalculationInterval: string
{
    case RECALCULATION_INTERVAL_UNSPECIFIED = 'RECALCULATION_INTERVAL_UNSPECIFIED';
    case ON_CHANGE = 'ON_CHANGE';
    case MINUTE = 'MINUTE';
    case HOUR = 'HOUR';
}
