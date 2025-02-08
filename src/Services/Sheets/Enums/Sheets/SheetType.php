<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Sheets;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets#recalculationinterval
 */
enum SheetType: string
{
    case SHEET_TYPE_UNSPECIFIED = 'SHEET_TYPE_UNSPECIFIED';
    case GRID = 'GRID';
    case OBJECT = 'OBJECT';
    case DATA_SOURCE = 'DATA_SOURCE';
}
