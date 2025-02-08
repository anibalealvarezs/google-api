<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#datalabelplacement
 */
enum DataLabelPlacement: string
{
    case DATA_LABEL_PLACEMENT_UNSPECIFIED = 'DATA_LABEL_PLACEMENT_UNSPECIFIED';
    case CENTER = 'CENTER';
    case LEFT = 'LEFT';
    case RIGHT = 'RIGHT';
    case ABOVE = 'ABOVE';
    case BELOW = 'BELOW';
    case INSIDE_END = 'INSIDE_END';
    case INSIDE_BASE = 'INSIDE_BASE';
    case OUTSIDE_END = 'OUTSIDE_END';
}
