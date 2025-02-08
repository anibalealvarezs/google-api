<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\Basic;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#BasicChartLegendPosition
 */
enum BasicChartLegendPosition: string
{
    case BASIC_CHART_LEGEND_POSITION_UNSPECIFIED = 'BASIC_CHART_LEGEND_POSITION_UNSPECIFIED';
    case BOTTOM_LEGEND = 'BOTTOM_LEGEND';
    case LEFT_LEGEND = 'LEFT_LEGEND';
    case RIGHT_LEGEND = 'RIGHT_LEGEND';
    case TOP_LEGEND = 'TOP_LEGEND';
    case NO_LEGEND = 'NO_LEGEND';
}
