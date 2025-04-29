<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages#notesproperties
 */
class NotesProperties implements Jsonable
{
    public string $speakerNotesObjectId;
    
    public function __construct(
        string $speakerNotesObjectId,
    ) {
        $this->speakerNotesObjectId = $speakerNotesObjectId;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
