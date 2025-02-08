<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\Waterfall;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#waterfallchartstackedtype
 */
enum WaterfallChartStackedType: string
{
    case WATERFALL_STACKED_TYPE_UNSPECIFIED = 'WATERFALL_STACKED_TYPE_UNSPECIFIED';
    case STACKED = 'STACKED';
    case SEQUENTIAL = 'SEQUENTIAL';
}
