<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Enums\Pages\Other;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/other#dashstyle
 */
enum DashStyle: string
{
    case DASH_STYLE_UNSPECIFIED = 'DASH_STYLE_UNSPECIFIED';
    case SOLID = 'SOLID';
    case DOT = 'DOT';
    case DASH = 'DASH';
    case DASH_DOT = 'DASH_DOT';
    case LONG_DASH = 'LONG_DASH';
    case LONG_DASH_DOT = 'LONG_DASH_DOT';
}
