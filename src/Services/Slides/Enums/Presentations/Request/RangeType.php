<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Enums\Presentations\Request;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#Type
 */
enum RangeType: string
{
    case RANGE_TYPE_UNSPECIFIED = 'RANGE_TYPE_UNSPECIFIED';
    case FIXED_RANGE = 'FIXED_RANGE';
    case FROM_START_INDEX = 'FROM_START_INDEX';
    case ALL = 'ALL';
}
