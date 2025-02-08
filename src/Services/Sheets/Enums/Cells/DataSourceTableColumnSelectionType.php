<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Cells;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/cells#datasourcetablecolumnselectiontype
 */
enum DataSourceTableColumnSelectionType: string 
{
    case DATA_SOURCE_TABLE_COLUMN_SELECTION_TYPE_UNSPECIFIED = 'DATA_SOURCE_TABLE_COLUMN_SELECTION_TYPE_UNSPECIFIED';
    case SELECTED = 'SELECTED';
    case SYNC_ALL = 'SYNC_ALL';
}
