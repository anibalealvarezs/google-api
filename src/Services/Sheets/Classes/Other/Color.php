<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#color
 */
class Color implements Jsonable
{
    public float $red;
    public float $green;
    public float $blue;
    public float $alpha;
    
    public function __construct(
        float $red = 0.0,
        float $green = 0.0,
        float $blue = 0.0,
        float $alpha = 1
    ) {
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
        $this->alpha = $alpha;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
