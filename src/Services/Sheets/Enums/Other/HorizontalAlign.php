<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Other;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#horizontalalign
 */
enum HorizontalAlign: string
{
    case HORIZONTAL_ALIGN_UNSPECIFIED = 'HORIZONTAL_ALIGN_UNSPECIFIED';
    case LEFT = 'LEFT';
    case CENTER = 'CENTER';
    case RIGHT = 'RIGHT';
}
