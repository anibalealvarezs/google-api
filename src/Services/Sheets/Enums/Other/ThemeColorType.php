<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Other;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#themecolortype
 */
enum ThemeColorType: string
{
    case THEME_COLOR_TYPE_UNSPECIFIED = 'THEME_COLOR_TYPE_UNSPECIFIED';
    case TEXT = 'TEXT';
    case BACKGROUND = 'BACKGROUND';
    case ACCENT1 = 'ACCENT1';
    case ACCENT2 = 'ACCENT2';
    case ACCENT3 = 'ACCENT3';
    case ACCENT4 = 'ACCENT4';
    case ACCENT5 = 'ACCENT5';
    case ACCENT6 = 'ACCENT6';
    case LINK = 'LINK';
}
