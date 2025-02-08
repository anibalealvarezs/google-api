<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Cells;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/cells#hyperlinkdisplaytype
 */
enum HyperlinkDisplayType: string
{
    case HYPERLINK_DISPLAY_TYPE_UNSPECIFIED = 'HYPERLINK_DISPLAY_TYPE_UNSPECIFIED';
    case LINKED = 'LINKED';
    case PLAIN_TEXT = 'PLAIN_TEXT';
}
