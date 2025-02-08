<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#pointshape
 */
enum PointShape: string
{
    case POINT_SHAPE_UNSPECIFIED = 'POINT_SHAPE_UNSPECIFIED';
    case CIRCLE = 'CIRCLE';
    case DIAMOND = 'DIAMOND';
    case HEXAGON = 'HEXAGON';
    case PENTAGON = 'PENTAGON';
    case SQUARE = 'SQUARE';
    case STAR = 'STAR';
    case TRIANGLE = 'TRIANGLE';
    case X_MARK = 'X_MARK';
}
