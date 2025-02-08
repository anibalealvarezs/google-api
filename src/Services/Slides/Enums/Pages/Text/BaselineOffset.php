<?php

namespace Chmw\GoogleApi\Services\Slides\Enums\Pages\Text;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/text#baselineoffset
 */
enum BaselineOffset: string
{
    case BASELINE_OFFSET_UNSPECIFIED = 'BASELINE_OFFSET_UNSPECIFIED';
    case NONE = 'NONE';
    case SUPERSCRIPT = 'SUPERSCRIPT';
    case SUBSCRIPT = 'SUBSCRIPT';
}
