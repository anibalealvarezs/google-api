<?php

namespace Chmw\GoogleApi\Services\Slides\Enums\Pages\Other;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/other#Page.Type_3
 */
enum PlaceholderType: string
{
    case NONE = 'NONE';
    case BODY = 'BODY';
    case CHART = 'CHART';
    case CLIP_ART = 'CLIP_ART';
    case CENTERED_TITLE = 'CENTERED_TITLE';
    case DIAGRAM = 'DIAGRAM';
    case DATE_AND_TIME = 'DATE_AND_TIME';
    case FOOTER = 'FOOTER';
    case HEADER = 'HEADER';
    case MEDIA = 'MEDIA';
    case OBJECT = 'OBJECT';
    case PICTURE = 'PICTURE';
    case SLIDE_NUMBER = 'SLIDE_NUMBER';
    case SUBTITLE = 'SUBTITLE';
    case TABLE = 'TABLE';
    case TITLE = 'TITLE';
    case SLIDE_IMAGE = 'SLIDE_IMAGE';
}
