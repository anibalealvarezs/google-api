<?php

namespace Chmw\GoogleApi\Services\BigQuery\Enums;

enum ParameterMode: string
{
    case POSITIONAL = 'POSITIONAL';
    case NAMED = 'NAMED';
}
