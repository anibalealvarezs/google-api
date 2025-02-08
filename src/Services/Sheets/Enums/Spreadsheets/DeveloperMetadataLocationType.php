<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Spreadsheets;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets.developerMetadata#developermetadatalocationtype
 */
enum DeveloperMetadataLocationType: string
{
    case DEVELOPER_METADATA_LOCATION_TYPE_UNSPECIFIED = 'DEVELOPER_METADATA_LOCATION_TYPE_UNSPECIFIED';
    case ROW = 'ROW';
    case COLUMN = 'COLUMN';
    case SHEET = 'SHEET';
    case SPREADSHEET = 'SPREADSHEET';
}
