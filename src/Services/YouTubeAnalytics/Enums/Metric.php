<?php

namespace Anibalealvarezs\GoogleApi\Services\YouTubeAnalytics\Enums;

enum Metric: string
{
    case VIEWS = 'views';
    case LIKES = 'likes';
    case DISLIKES = 'dislikes';
    case SHARES = 'shares';
    case COMMENTS = 'comments';
    case ESTIMATED_MINUTES_WATCHED = 'estimatedMinutesWatched';
    case AVERAGE_VIEW_DURATION = 'averageViewDuration';
    case SUBSCRIBERS_GAINED = 'subscribersGained';
    case SUBSCRIBERS_LOST = 'subscribersLost';
}
