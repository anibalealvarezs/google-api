<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Other;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#sortorder
 */
enum SortOrder: string
{
    case SORT_ORDER_UNSPECIFIED = 'SORT_ORDER_UNSPECIFIED';
    case ASCENDING = 'ASCENDING';
    case DESCENDING = 'DESCENDING';
}
