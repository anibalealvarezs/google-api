<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Cells;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/cells#padding
 */
class Padding implements Jsonable
{
    public int $top;
    public int $right;
    public int $bottom;
    public int $left;
    
    public function __construct(
        int $top,
        int $right,
        int $bottom,
        int $left
    ) {
        $this->top = $top;
        $this->right = $right;
        $this->bottom = $bottom;
        $this->left = $left;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
