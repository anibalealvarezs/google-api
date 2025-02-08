<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages\Other;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/other#Page.RgbColor
 */
class RgbColor implements Jsonable
{
    public ?float $red;
    public ?float $green;
    public ?float $blue;
    
    public function __construct(
        ?float $red = null,
        ?float $green = null,
        ?float $blue = null,
    ) {
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
