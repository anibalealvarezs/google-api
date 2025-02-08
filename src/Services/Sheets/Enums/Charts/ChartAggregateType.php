<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#chartaggregatetype
 */
enum ChartAggregateType: string
{
    case CHART_AGGREGATE_TYPE_UNSPECIFIED = 'CHART_AGGREGATE_TYPE_UNSPECIFIED';
    case AVERAGE = 'AVERAGE';
    case COUNT = 'COUNTA';
    case MAX = 'MAX';
    case MEDIAN = 'MEDIAN';
    case MIN = 'MIN';
    case SUM = 'SUM';
}
