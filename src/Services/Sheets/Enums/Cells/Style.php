<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Cells;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/cells#style
 */
enum Style: string
{
    case STYLE_UNSPECIFIED = 'STYLE_UNSPECIFIED';
    case DOTTED = 'DOTTED';
    case DASHED = 'DASHED';
    case SOLID = 'SOLID';
    case SOLID_MEDIUM = 'SOLID_MEDIUM';
    case SOLID_THICK = 'SOLID_THICK';
    case NONE = 'NONE';
    case DOUBLE = 'DOUBLE';
}
