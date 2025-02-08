<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\PivotTables;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/pivot-tables#pivotvaluelayout
 */
enum PivotValueLayout: string
{
    case HORIZONTAL = 'HORIZONTAL';
    case VERTICAL = 'VERTICAL';
}
