<?php

namespace Chmw\GoogleApi\Services\Slides\Enums\Pages\Text;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/text#alignment
 */
enum Alignment: string
{
    case ALIGNMENT_UNSPECIFIED = 'ALIGNMENT_UNSPECIFIED';
    case START = 'START';
    case CENTER = 'CENTER';
    case END = 'END';
    case JUSTIFIED = 'JUSTIFIED';
}
