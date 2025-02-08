<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\PivotTables;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/pivot-tables#pivotvaluesummarizefunction
 */
enum PivotValueSummarizeFunction: string
{
    case PIVOT_STANDARD_VALUE_FUNCTION_UNSPECIFIED = 'PIVOT_STANDARD_VALUE_FUNCTION_UNSPECIFIED';
    case SUM = 'SUM';
    case COUNTA = 'COUNTA';
    case COUNT = 'COUNT';
    case COUNTUNIQUE = 'COUNTUNIQUE';
    case AVERAGE = 'AVERAGE';
    case MAX = 'MAX';
    case MIN = 'MIN';
    case MEDIAN = 'MEDIAN';
    case PRODUCT = 'PRODUCT';
    case STDEV = 'STDEV';
    case STDEVP = 'STDEVP';
    case VAR = 'VAR';
    case VARP = 'VARP';
    case CUSTOM = 'CUSTOM';
}
