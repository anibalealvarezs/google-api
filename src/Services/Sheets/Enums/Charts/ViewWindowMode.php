<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#viewwindowmode
 */
enum ViewWindowMode: string
{
    case DEFAULT_VIEW_WINDOW_MODE = 'DEFAULT_VIEW_WINDOW_MODE';
    case VIEW_WINDOW_MODE_UNSUPPORTED = 'VIEW_WINDOW_MODE_UNSUPPORTED';
    case EXPLICIT = 'EXPLICIT';
    case PRETTY = 'PRETTY';
}
