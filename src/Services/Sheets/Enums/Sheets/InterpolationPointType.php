<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Sheets;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/sheets#interpolationpointtype
 */
enum InterpolationPointType: string
{
    case INTERPOLATION_POINT_TYPE_UNSPECIFIED = 'INTERPOLATION_POINT_TYPE_UNSPECIFIED';
    case MIN = 'MIN';
    case MAX = 'MAX';
    case NUMBER = 'NUMBER';
    case PERCENT = 'PERCENT';
    case PERCENTILE = 'PERCENTILE';
}
