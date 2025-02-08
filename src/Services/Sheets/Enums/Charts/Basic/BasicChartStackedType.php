<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\Basic;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#basicchartstackedtype
 */
enum BasicChartStackedType: string
{
    case BASIC_CHART_STACKED_TYPE_UNSPECIFIED = 'BASIC_CHART_STACKED_TYPE_UNSPECIFIED';
    case NOT_STACKED = 'NOT_STACKED';
    case STACKED = 'STACKED';
    case PERCENT_STACKED = 'PERCENT_STACKED';
}
