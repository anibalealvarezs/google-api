<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Spreadsheets;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets.developerMetadata#developermetadatavisibility
 */
enum DeveloperMetadataVisibility: string
{
    case DEVELOPER_METADATA_VISIBILITY_UNSPECIFIED = 'DEVELOPER_METADATA_VISIBILITY_UNSPECIFIED';
    case DOCUMENT = 'DOCUMENT';
    case PROJECT = 'PROJECT';
}
