<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\PivotTables;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/pivot-tables#datetimeruletype
 */
enum DateTimeRuleType: string
{
    case DATE_TIME_RULE_TYPE_UNSPECIFIED = 'DATE_TIME_RULE_TYPE_UNSPECIFIED';
    case SECOND = 'SECOND';
    case MINUTE = 'MINUTE';
    case HOUR = 'HOUR';
    case HOUR_MINUTE = 'HOUR_MINUTE';
    case HOUR_MINUTE_AMPM = 'HOUR_MINUTE_AMPM';
    case DAY_OF_WEEK = 'DAY_OF_WEEK';
    case DAY_OF_YEAR = 'DAY_OF_YEAR';
    case DAY_OF_MONTH = 'DAY_OF_MONTH';
    case DAY_MONTH = 'DAY_MONTH';
    case MONTH = 'MONTH';
    case QUARTER = 'QUARTER';
    case YEAR = 'YEAR';
    case YEAR_MONTH = 'YEAR_MONTH';
    case YEAR_QUARTER = 'YEAR_QUARTER';
    case YEAR_MONTH_DAY = 'YEAR_MONTH_DAY';
}
