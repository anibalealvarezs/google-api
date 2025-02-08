<?php

namespace Chmw\GoogleApi\Services\Slides\Enums\Pages\Videos;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/videos#Page.Source
 */
enum Source: string
{
    case SOURCE_UNSPECIFIED = 'SOURCE_UNSPECIFIED';
    case YOUTUBE = 'YOUTUBE';
    case DRIVE = 'DRIVE';
}
