<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Cells;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets.values/append#InsertDataOption
 */
enum InsertDataOption: string
{
    case OVERWRITE = 'OVERWRITE';
    case INSERT_ROWS = 'INSERT_ROWS';
}
