<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/Dimension
 */
enum Dimension: string 
{
    case DIMENSION_UNSPECIFIED = 'DIMENSION_UNSPECIFIED';
    case ROWS = 'ROWS';
    case COLUMNS = 'COLUMNS';
}
