<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/ValueRenderOption
 */
enum ValueRenderOption: string
{
    case FORMATTED_VALUE = 'FORMATTED_VALUE';
    case UNFORMATTED_VALUE = 'UNFORMATTED_VALUE';
    case FORMULA = 'FORMULA';
}
