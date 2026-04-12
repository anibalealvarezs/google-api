<?php

namespace Anibalealvarezs\GoogleApi\Services\AnalyticsData\Enums;

enum Dimension: string
{
    case DATE = 'date';
    case CITY = 'city';
    case COUNTRY = 'country';
    case DEVICE_CATEGORY = 'deviceCategory';
    case PAGE_PATH = 'pagePath';
    case SOURCE = 'source';
    case MEDIUM = 'medium';
    case CAMPAIGN = 'campaignName';
}
