<?php

namespace Chmw\GoogleApi\Services\Slides\Enums\Pages\Lines;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/lines#Page.LineCategory
 */
enum LineCategory: string
{
    case LINE_CATEGORY_UNSPECIFIED = 'LINE_CATEGORY_UNSPECIFIED';
    case STRAIGHT = 'STRAIGHT';
    case BENT = 'BENT';
    case CURVED = 'CURVED';
}
