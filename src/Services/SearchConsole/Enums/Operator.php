<?php

namespace Anibalealvarezs\GoogleApi\Services\SearchConsole\Enums;

enum Operator: string
{
    case CONTAINS = 'CONTAINS';
    case EQUALS = 'EQUALS';
    case NOT_CONTAINS = 'NOT_CONTAINS';
    case NOT_EQUALS = 'NOT_EQUALS';
    case INCLUDING_REGEX = 'INCLUDING_REGEX';
    case EXCLUDING_REGEX = 'EXCLUDING_REGEX';
}
