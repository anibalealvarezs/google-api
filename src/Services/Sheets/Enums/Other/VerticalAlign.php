<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Other;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#horizontalalign
 */
enum VerticalAlign: string
{
    case VERTICAL_ALIGN_UNSPECIFIED = 'VERTICAL_ALIGN_UNSPECIFIED';
    case TOP = 'TOP';
    case MIDDLE = 'MIDDLE';
    case BOTTOM = 'BOTTOM';
}
