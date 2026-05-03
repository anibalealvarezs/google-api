<?php

namespace Anibalealvarezs\GoogleApi\Services\YouTubeAnalytics\Enums;

enum Dimension: string
{
    case DAY = 'day';
    case MONTH = 'month';
    case VIDEO = 'video';
    case CHANNEL = 'channel';
    case COUNTRY = 'country';
    case GENDER = 'gender';
    case AGE_GROUP = 'ageGroup';
}
