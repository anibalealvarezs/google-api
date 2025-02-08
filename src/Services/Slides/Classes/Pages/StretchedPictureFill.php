<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Size;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages#stretchedpicturefill
 */
class StretchedPictureFill implements Jsonable
{
    public string $contentUrl;
    public readonly Size|array $size;
    
    public function __construct(
        string $contentUrl,
        Size|array $size,
    ) {
        $this->contentUrl = $contentUrl;
        $this->size = $this->arrayToObject(class: Size::class, var: $size);
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
