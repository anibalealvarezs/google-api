<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#datalabeltype
 */
enum DataLabelType: string
{
    case DATA_LABEL_TYPE_UNSPECIFIED = 'DATA_LABEL_TYPE_UNSPECIFIED';
    case NONE = 'NONE';
    case DATA = 'DATA';
    case CUSTOM = 'CUSTOM';
}
