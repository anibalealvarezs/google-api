<?php

namespace Chmw\GoogleApi\Services\Slides\Enums\Pages\Other;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/other#contentalignment
 */
enum ContentAlignment: string
{
    case CONTENT_ALIGNMENT_UNSPECIFIED = 'CONTENT_ALIGNMENT_UNSPECIFIED';
    case CONTENT_ALIGNMENT_UNSUPPORTED = 'CONTENT_ALIGNMENT_UNSUPPORTED';
    case TOP = 'TOP';
    case MIDDLE = 'MIDDLE';
    case BOTTOM = 'BOTTOM';
}
