<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Enums\Pages\Text;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/text#spacingmode
 */
enum SpacingMode: string
{
    case SPACING_MODE_UNSPECIFIED = 'SPACING_MODE_UNSPECIFIED';
    case NEVER_COLLAPSE = 'NEVER_COLLAPSE';
    case COLLAPSE_LISTS = 'COLLAPSE_LISTS';
}
