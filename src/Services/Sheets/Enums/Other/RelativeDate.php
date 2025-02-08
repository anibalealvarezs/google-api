<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Other;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#relativedate
 */
enum RelativeDate: string
{
    case RELATIVE_DATE_UNSPECIFIED = 'RELATIVE_DATE_UNSPECIFIED';
    case PAST_YEAR = 'PAST_YEAR';
    case PAST_MONTH = 'PAST_MONTH';
    case PAST_WEEK = 'PAST_WEEK';
    case YESTERDAY = 'YESTERDAY';
    case TODAY = 'TODAY';
    case TOMORROW = 'TOMORROW';
}
