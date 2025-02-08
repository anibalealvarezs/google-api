<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#charthiddendimensionstrategy
 */
enum ChartHiddenDimensionStrategy: string
{
    case CHART_HIDDEN_DIMENSION_STRATEGY_UNSPECIFIED = 'CHART_HIDDEN_DIMENSION_STRATEGY_UNSPECIFIED';
    case SKIP_HIDDEN_ROWS_AND_COLUMNS = 'SKIP_HIDDEN_ROWS_AND_COLUMNS';
    case SKIP_HIDDEN_ROWS = 'SKIP_HIDDEN_ROWS';
    case SKIP_HIDDEN_COLUMNS = 'SKIP_HIDDEN_COLUMNS';
    case SHOW_ALL = 'SHOW_ALL';
}
