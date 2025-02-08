<?php

namespace Chmw\GoogleApi\Services\Slides\Enums\Presentations\Request;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#linkingmode
 */
enum LinkingMode: string
{
    case NOT_LINKED_IMAGE = 'NOT_LINKED_IMAGE';
    case LINKED = 'LINKED';
}
