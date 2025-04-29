<?php

namespace Anibalealvarezs\GoogleApi\Services\SearchConsole\Enums;

enum Operator: string
{
    case CONTAINS = 'contains';
    case EQUALS = 'equals';
    case NOT_CONTAINS = 'notContains';
    case NOT_EQUALS = 'notEquals';
    case INCLUDING_REGEX = 'includingRegex';
    case EXCLUDING_REGEX = 'excludingRegex';
}
