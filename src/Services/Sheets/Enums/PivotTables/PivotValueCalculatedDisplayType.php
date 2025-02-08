<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\PivotTables;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/pivot-tables#pivotvaluecalculateddisplaytype
 */
enum PivotValueCalculatedDisplayType: string
{
    case PIVOT_VALUE_CALCULATED_DISPLAY_TYPE_UNSPECIFIED = 'PIVOT_VALUE_CALCULATED_DISPLAY_TYPE_UNSPECIFIED';
    case PERCENT_OF_ROW_TOTAL = 'PERCENT_OF_ROW_TOTAL';
    case PERCENT_OF_COLUMN_TOTAL = 'PERCENT_OF_COLUMN_TOTAL';
    case PERCENT_OF_GRAND_TOTAL = 'PERCENT_OF_GRAND_TOTAL';
}
