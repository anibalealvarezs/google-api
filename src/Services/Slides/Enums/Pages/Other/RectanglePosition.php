<?php

namespace Chmw\GoogleApi\Services\Slides\Enums\Pages\Other;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/other#rectangleposition
 */
enum RectanglePosition: string
{
    case RECTANGLE_POSITION_UNSPECIFIED = 'RECTANGLE_POSITION_UNSPECIFIED';
    case TOP_LEFT = 'TOP_LEFT';
    case TOP_CENTER = 'TOP';
    case TOP_RIGHT = 'TOP_RIGHT';
    case LEFT_CENTER = 'LEFT_CENTER';
    case CENTER = 'CENTER';
    case RIGHT_CENTER = 'RIGHT_CENTER';
    case BOTTOM_LEFT = 'BOTTOM_LEFT';
    case BOTTOM_CENTER = 'BOTTOM_CENTER';
    case BOTTOM_RIGHT = 'BOTTOM_RIGHT';
}
