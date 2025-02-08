<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

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
