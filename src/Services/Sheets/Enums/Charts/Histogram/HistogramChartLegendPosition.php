<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\Histogram;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#histogramchartlegendposition
 */
enum HistogramChartLegendPosition: string
{
    case HISTOGRAM_CHART_LEGEND_POSITION_UNSPECIFIED = 'HISTOGRAM_CHART_LEGEND_POSITION_UNSPECIFIED';
    case BOTTOM_LEGEND = 'BOTTOM_LEGEND';
    case LEFT_LEGEND = 'LEFT_LEGEND';
    case RIGHT_LEGEND = 'RIGHT_LEGEND';
    case TOP_LEGEND = 'TOP_LEGEND';
    case NO_LEGEND = 'NO_LEGEND';
    case INSIDE_LEGEND = 'INSIDE_LEGEND';
}
