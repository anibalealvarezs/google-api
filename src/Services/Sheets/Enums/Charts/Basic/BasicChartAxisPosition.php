<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\Basic;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#basicchartaxisposition
 */
enum BasicChartAxisPosition: string
{
    case BASIC_CHART_AXIS_POSITION_UNSPECIFIED = 'BASIC_CHART_AXIS_POSITION_UNSPECIFIED';
    case BOTTOM_AXIS = 'BOTTOM_AXIS';
    case LEFT_AXIS = 'LEFT_AXIS';
    case RIGHT_AXIS = 'RIGHT_AXIS';
}
