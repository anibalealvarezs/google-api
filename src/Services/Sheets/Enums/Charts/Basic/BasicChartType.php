<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\Basic;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#basiccharttype
 */
enum BasicChartType: string
{
    case BASIC_CHART_TYPE_UNSPECIFIED = 'BASIC_CHART_TYPE_UNSPECIFIED';
    case BAR = 'BAR';
    case LINE = 'LINE';
    case AREA = 'AREA';
    case COLUMN = 'COLUMN';
    case SCATTER = 'SCATTER';
    case COMBO = 'COMBO';
    case STEPPED_AREA = 'STEPPED_AREA';
}
