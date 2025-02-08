<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Cells;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/cells#wrapstrategy
 */
enum WrapStrategy: string
{
    case WRAP_STRATEGY_UNSPECIFIED = 'WRAP_STRATEGY_UNSPECIFIED';
    case OVERFLOW_CELL = 'OVERFLOW_CELL';
    case LEGACY_WRAP = 'LEGACY_WRAP';
    case CLIP = 'CLIP';
    case WRAP = 'WRAP';
}
