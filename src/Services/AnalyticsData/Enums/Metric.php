<?php

namespace Anibalealvarezs\GoogleApi\Services\AnalyticsData\Enums;

enum Metric: string
{
    case ACTIVE_USERS = 'activeUsers';
    case SESSIONS = 'sessions';
    case SCREEN_PAGE_VIEWS = 'screenPageViews';
    case EVENT_COUNT = 'eventCount';
    case CONVERSIONS = 'conversions';
    case BOUNCE_RATE = 'bounceRate';
    case NEW_USERS = 'newUsers';
}
