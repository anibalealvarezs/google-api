<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums\Charts;

enum ChartTypes: string
{
    case BASIC = 'BASIC';
    case PIE = 'PIE';
    case BUBBLE = 'BUBBLE';
    case CANDLESTICK = 'CANDLESTICK';
    case ORG = 'ORG';
    case HISTOGRAM = 'HISTOGRAM';
    case WATERFALL = 'WATERFALL';
    case TREEMAP = 'TREEMAP';
    case SCORECARD = 'SCORECARD';
}
