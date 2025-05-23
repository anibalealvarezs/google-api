<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Enums\Pages\Other;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/other#Page.ThemeColorType
 */
enum ThemeColorType: string
{
    case THEME_COLOR_TYPE_UNSPECIFIED = 'THEME_COLOR_TYPE_UNSPECIFIED';
    case DARK1 = 'DARK1';
    case LIGHT1 = 'LIGHT1';
    case DARK2 = 'DARK2';
    case LIGHT2 = 'LIGHT2';
    case ACCENT1 = 'ACCENT1';
    case ACCENT2 = 'ACCENT2';
    case ACCENT3 = 'ACCENT3';
    case ACCENT4 = 'ACCENT4';
    case ACCENT5 = 'ACCENT5';
    case ACCENT6 = 'ACCENT6';
    case HYPERLINK = 'HYPERLINK';
    case FOLLOWED_HYPERLINK = 'FOLLOWED_HYPERLINK';
    case TEXT1 = 'TEXT1';
    case BACKGROUND1 = 'BACKGROUND1';
    case TEXT2 = 'TEXT2';
    case BACKGROUND2 = 'BACKGROUND2';
}
