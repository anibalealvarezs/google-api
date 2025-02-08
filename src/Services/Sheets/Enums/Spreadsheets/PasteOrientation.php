<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Spreadsheets;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/request#pasteorientation
 */
enum PasteOrientation: string
{
    case NORMAL = 'NORMAL';
    case TRANSPOSE = 'TRANSPOSE';
}
