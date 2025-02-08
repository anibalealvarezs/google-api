<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/DateTimeRenderOption
 */
enum DateTimeRenderOption: string
{
    case SERIAL_NUMBER = 'SERIAL_NUMBER';
    case FORMATTED_STRING = 'FORMATTED_STRING';
}
