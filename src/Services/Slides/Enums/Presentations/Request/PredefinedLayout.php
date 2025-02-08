<?php

namespace Chmw\GoogleApi\Services\Slides\Enums\Presentations\Request;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#predefinedlayout
 */
enum PredefinedLayout: string
{
    case PREDEFINED_LAYOUT_UNSPECIFIED = 'PREDEFINED_LAYOUT_UNSPECIFIED';
    case BLANK = 'BLANK';
    case CAPTION_ONLY = 'CAPTION_ONLY';
    case TITLE = 'TITLE';
    case TITLE_AND_BODY = 'TITLE_AND_BODY';
    case TITLE_AND_TWO_COLUMNS = 'TITLE_AND_TWO_COLUMNS';
    case TITLE_ONLY = 'TITLE_ONLY';
    case SECTION_HEADER = 'SECTION_HEADER';
    case SECTION_TITLE_AND_DESCRIPTION = 'SECTION_TITLE_AND_DESCRIPTION';
    case ONE_COLUMN_TEXT = 'ONE_COLUMN_TEXT';
    case MAIN_POINT = 'MAIN_POINT';
    case BIG_NUMBER = 'BIG_NUMBER';
}
