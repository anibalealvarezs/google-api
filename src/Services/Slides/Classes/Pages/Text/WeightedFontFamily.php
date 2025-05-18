<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Text;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/text#weightedfontfamily
 */
class WeightedFontFamily implements Jsonable
{
    public ?string $fontFamily;
    public int $weight;
    
    public function __construct(
        string $fontFamily = 'Arial',
        int $weight = 400,
    ) {
        $this->fontFamily = $fontFamily;
        $this->weight = $weight;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
