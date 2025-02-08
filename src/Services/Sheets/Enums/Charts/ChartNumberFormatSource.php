<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#chartnumberformatsource
 */
enum ChartNumberFormatSource: string
{
    case CHART_NUMBER_FORMAT_SOURCE_UNDEFINED = 'CHART_NUMBER_FORMAT_SOURCE_UNDEFINED';
    case FROM_DATA = 'FROM_DATA';
    case CUSTOM = 'CUSTOM';
}
