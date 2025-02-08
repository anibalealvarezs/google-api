<?php

namespace Chmw\GoogleApi\Services\Slides\Enums\Pages\Lines;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/lines#arrowstyle
 */
enum ArrowStyle: string
{
    case ARROW_STYLE_UNSPECIFIED = 'ARROW_STYLE_UNSPECIFIED';
    case NONE = 'NONE';
    case STEALTH_ARROW = 'STEALTH_ARROW';
    case FILL_ARROW = 'FILL_ARROW';
    case FILL_CIRCLE = 'FILL_CIRCLE';
    case FILL_SQUARE = 'FILL_SQUARE';
    case FILL_DIAMOND = 'FILL_DIAMOND';
    case OPEN_ARROW = 'OPEN_ARROW';
    case OPEN_CIRCLE = 'OPEN_CIRCLE';
    case OPEN_SQUARE = 'OPEN_SQUARE';
    case OPEN_DIAMOND = 'OPEN_DIAMOND';
}
