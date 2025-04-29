<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages#wordart
 */
class WordArt implements Jsonable
{
    public string $renderedText;
    
    public function __construct(
        string $renderedText,
    ) {
        $this->renderedText = $renderedText;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
