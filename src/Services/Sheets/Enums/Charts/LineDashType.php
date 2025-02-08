<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#linedashtype
 */
enum LineDashType: string
{
    case LINE_DASH_TYPE_UNSPECIFIED = 'LINE_DASH_TYPE_UNSPECIFIED';
    case INVISIBLE = 'INVISIBLE';
    case CUSTOM = 'CUSTOM';
    case SOLID = 'SOLID';
    case DOTTED = 'DOTTED';
    case MEDIUM_DASHED = 'MEDIUM_DASHED';
    case MEDIUM_DASHED_DOTTED = 'MEDIUM_DASHED_DOTTED';
    case LONG_DASHED = 'LONG_DASHED';
    case LONG_DASHED_DOTTED = 'LONG_DASHED_DOTTED';
}
