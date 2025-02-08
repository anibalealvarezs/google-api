<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Cells;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/cells#numberformattype
 */
enum NumberFormatType: string
{
    case NUMBER_FORMAT_TYPE_UNSPECIFIED = 'NUMBER_FORMAT_TYPE_UNSPECIFIED';
    case NUMBER = 'NUMBER';
    case PERCENT = 'PERCENT';
    case CURRENCY = 'CURRENCY';
    case DATE = 'DATE';
    case TIME = 'TIME';
    case DATE_TIME = 'DATE_TIME';
    case SCIENTIFIC = 'SCIENTIFIC';
}
