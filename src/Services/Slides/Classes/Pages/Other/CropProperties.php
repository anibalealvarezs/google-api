<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages\Other;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/other#Page.CropProperties
 */
class CropProperties implements Jsonable
{
    public float $leftOffset;
    public float $rightOffset;
    public float $topOffset;
    public float $bottomOffset;
    public float $angle;
    
    public function __construct(
        float $leftOffset = 0.0,
        float $rightOffset = 0.0,
        float $topOffset = 0.0,
        float $bottomOffset = 0.0,
        float $angle = 0.0
    ) {
        $this->leftOffset = $leftOffset;
        $this->rightOffset = $rightOffset;
        $this->topOffset = $topOffset;
        $this->bottomOffset = $bottomOffset;
        $this->angle = $angle;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
