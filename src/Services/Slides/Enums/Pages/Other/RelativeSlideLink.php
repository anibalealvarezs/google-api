<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Enums\Pages\Other;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/other#relativeslidelink
 */
enum RelativeSlideLink: string
{
    case RELATIVE_SLIDE_LINK_UNSPECIFIED = 'RELATIVE_SLIDE_LINK_UNSPECIFIED';
    case NEXT_SLIDE = 'NEXT_SLIDE';
    case PREVIOUS_SLIDE = 'PREVIOUS_SLIDE';
    case FIRST_SLIDE = 'FIRST_SLIDE';
    case LAST_SLIDE = 'LAST_SLIDE';
}
