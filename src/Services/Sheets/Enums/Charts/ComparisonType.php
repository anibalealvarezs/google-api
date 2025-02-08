<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#comparisontype
 */
enum ComparisonType: string
{
    case COMPARISON_TYPE_UNDEFINED = 'COMPARISON_TYPE_UNDEFINED';
    case ABSOLUTE_DIFFERENCE = 'ABSOLUTE_DIFFERENCE';
    case PERCENTAGE_DIFFERENCE = 'PERCENTAGE_DIFFERENCE';
}
