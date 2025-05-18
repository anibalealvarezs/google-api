<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Enums\Pages\Text;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/text#textdirection
 */
enum TextDirection: string
{
    case TEXT_DIRECTION_UNSPECIFIED = 'TEXT_DIRECTION_UNSPECIFIED';
    case LEFT_TO_RIGHT = 'LEFT_TO_RIGHT';
    case RIGHT_TO_LEFT = 'RIGHT_TO_LEFT';
}
