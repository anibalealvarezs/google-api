<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages#slideproperties
 */
class SlideProperties implements Jsonable
{
    public readonly string $layoutObjectId;
    public readonly string $masterObjectId;
    public readonly Page|array $notesPage;
    public bool $isSkipped;
    
    public function __construct(
        string $layoutObjectId,
        string $masterObjectId,
        Page|array $notesPage,
        bool $isSkipped = false,
    ) {
        $this->layoutObjectId = $layoutObjectId;
        $this->masterObjectId = $masterObjectId;
        $this->notesPage = $this->arrayToObject(class: Page::class, var: $notesPage);
        $this->isSkipped = $isSkipped;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }

    public function arrayToObject(string $class, mixed $var): mixed
    {
        if (is_array($var)) {
            return new $class(...$var);
        }
        return $var;
    }
}
