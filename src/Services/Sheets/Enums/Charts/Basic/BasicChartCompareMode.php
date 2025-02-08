<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\Basic;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#basicchartcomparemode
 */
enum BasicChartCompareMode: string
{
    case BASIC_CHART_COMPARE_MODE_UNSPECIFIED = 'BASIC_CHART_COMPARE_MODE_UNSPECIFIED';
    case DATUM = 'DATUM';
    case CATEGORY = 'CATEGORY';
}
