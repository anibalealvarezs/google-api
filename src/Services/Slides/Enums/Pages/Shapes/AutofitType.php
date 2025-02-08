<?php

namespace Chmw\GoogleApi\Services\Slides\Enums\Pages\Shapes;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/shapes#Page.AutofitType
 */
enum AutofitType: string
{
    case AUTOFIT_TYPE_UNSPECIFIED = 'AUTOFIT_TYPE_UNSPECIFIED';
    case NONE = 'NONE';
    case TEXT_AUTOFIT = 'TEXT_AUTOFIT';
    case SHAPE_AUTOFIT = 'SHAPE_AUTOFIT';
}
