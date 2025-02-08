<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts\Org;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#orgchartnodesize
 */
enum OrgChartNodeSize: string
{
    case ORG_CHART_LABEL_SIZE_UNSPECIFIED = 'ORG_CHART_LABEL_SIZE_UNSPECIFIED';
    case SMALL = 'SMALL';
    case MEDIUM = 'MEDIUM';
    case LARGE = 'LARGE';
}
