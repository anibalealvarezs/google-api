<?php

namespace Chmw\GoogleApi\Services\Slides\Enums\Pages\Lines;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/lines#type
 */
enum Type: string
{
    case TYPE_UNSPECIFIED = 'TYPE_UNSPECIFIED';
    case STRAIGHT_CONNECTOR_1 = 'STRAIGHT_CONNECTOR_1';
    case BENT_CONNECTOR_2 = 'BENT_CONNECTOR_2';
    case BENT_CONNECTOR_3 = 'BENT_CONNECTOR_3';
    case BENT_CONNECTOR_4 = 'BENT_CONNECTOR_4';
    case BENT_CONNECTOR_5 = 'BENT_CONNECTOR_5';
    case CURVED_CONNECTOR_2 = 'CURVED_CONNECTOR_2';
    case CURVED_CONNECTOR_3 = 'CURVED_CONNECTOR_3';
    case CURVED_CONNECTOR_4 = 'CURVED_CONNECTOR_4';
    case CURVED_CONNECTOR_5 = 'CURVED_CONNECTOR_5';
    case STRAIGHT_LINE = 'STRAIGHT_LINE';
}
