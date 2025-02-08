<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Enums;

enum ClearSheetMethods: string
{
    case CLEAR_CELLS = 'CLEAR_CELLS';
    case UPDATE_CELLS = 'UPDATE_CELLS';
    case WRITE_CELLS = 'WRITE_CELLS';
}
