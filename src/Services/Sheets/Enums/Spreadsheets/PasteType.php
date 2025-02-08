<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Spreadsheets;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/request#pastetype
 */
enum PasteType: string
{
    case PASTE_NORMAL = 'PASTE_NORMAL';
    case PASTE_VALUES = 'PASTE_VALUES';
    case PASTE_FORMAT = 'PASTE_FORMAT';
    case PASTE_NO_BORDERS = 'PASTE_NO_BORDERS';
    case PASTE_FORMULA = 'PASTE_FORMULA';
    case PASTE_DATA_VALIDATION = 'PASTE_DATA_VALIDATION';
    case PASTE_CONDITIONAL_FORMATTING = 'PASTE_CONDITIONAL_FORMATTING';
}
