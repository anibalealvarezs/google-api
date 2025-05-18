<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Enums\Pages\Other;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/other#propertystate
 */
enum PropertyState: string
{
    case RENDERED = 'RENDERED';
    case NOT_RENDERED = 'NOT_RENDERED';
    case INHERIT = 'INHERIT';
}
