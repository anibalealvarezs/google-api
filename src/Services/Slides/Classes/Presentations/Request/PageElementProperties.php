<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Presentations\Request;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Pages\Other\AffineTransform;
use Chmw\GoogleApi\Services\Slides\Classes\Size;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#pageelementproperties
 */
class PageElementProperties implements Jsonable
{
    public string $pageObjectId;
    public Size|array|null $size;
    public AffineTransform|array|null $transform;
    
    public function __construct(
        string $pageObjectId,
        Size|array|null $size = null,
        AffineTransform|array|null $transform = null,
    ) {
        $this->pageObjectId = $pageObjectId;
        $this->size = $this->arrayToObject(class: Size::class, var: $size);
        $this->transform = $this->arrayToObject(class: AffineTransform::class, var: $transform);
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
