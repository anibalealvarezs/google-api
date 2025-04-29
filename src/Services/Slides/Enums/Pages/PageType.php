<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Enums\Pages;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages#pagetype
 */
enum PageType: string
{
    case SLIDE = 'SLIDE';
    case MASTER = 'MASTER';
    case LAYOUT = 'LAYOUT';
    case NOTES = 'NOTES';
    case NOTES_MASTER = 'NOTES_MASTER';
}
