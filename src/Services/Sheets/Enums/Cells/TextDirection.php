<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Cells;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/cells#textdirection
 */
enum TextDirection: string
{
    case TEXT_DIRECTION_UNSPECIFIED = 'TEXT_DIRECTION_UNSPECIFIED';
    case LEFT_TO_RIGHT = 'LEFT_TO_RIGHT';
    case RIGHT_TO_LEFT = 'RIGHT_TO_LEFT';
}
