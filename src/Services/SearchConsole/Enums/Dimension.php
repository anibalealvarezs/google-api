<?php

namespace Anibalealvarezs\GoogleApi\Services\SearchConsole\Enums;

enum Dimension: string
{
    case COUNTRY = 'country';
    case DEVICE = 'device';
    case PAGE = 'page';
    case QUERY = 'query';
    case SEARCH_APPEARANCE = 'searchAppearance';
}
