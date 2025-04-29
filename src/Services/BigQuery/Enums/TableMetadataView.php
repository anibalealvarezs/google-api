<?php

namespace Anibalealvarezs\GoogleApi\Services\BigQuery\Enums;

enum TableMetadataView: string
{
    case TABLE_METADATA_VIEW_UNSPECIFIED = 'TABLE_METADATA_VIEW_UNSPECIFIED';
    case BASIC = 'BASIC';
    case STORAGE_STATS = 'STORAGE_STATS';
    case FULL = 'FULL';
}
