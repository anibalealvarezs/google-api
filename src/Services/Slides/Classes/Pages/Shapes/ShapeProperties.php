<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages\Shapes;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Pages\Other\Link;
use Chmw\GoogleApi\Services\Slides\Classes\Pages\Other\Outline;
use Chmw\GoogleApi\Services\Slides\Classes\Pages\Other\Shadow;
use Chmw\GoogleApi\Services\Slides\Enums\Pages\Other\ContentAlignment;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/shapes#Page.ShapeProperties
 */
class ShapeProperties implements Jsonable
{
    public ShapeBackgroundFill|array|null $shapeBackgroundFill;
    public Outline|array|null $outline;
    public Shadow|array|null $shadow;
    public Link|array|null $link;
    public ContentAlignment|string $contentAlignment;
    public Autofit|array|null $autofit;

    public function __construct(
        ShapeBackgroundFill|array|null $shapeBackgroundFill = null,
        Outline|array|null $outline = null,
        Shadow|array|null $shadow = null,
        Link|array|null $link = null,
        ContentAlignment|string $contentAlignment = ContentAlignment::CONTENT_ALIGNMENT_UNSPECIFIED,
        Autofit|array|null $autofit = null,
    ) {
        $this->shapeBackgroundFill = $this->arrayToObject(class: ShapeBackgroundFill::class, var: $shapeBackgroundFill);
        $this->outline = $this->arrayToObject(class: Outline::class, var: $outline);
        $this->shadow = $this->arrayToObject(class: Shadow::class, var: $shadow);
        $this->link = $this->arrayToObject(class: Link::class, var: $link);
        $this->contentAlignment = $this->stringToEnum(enum: ContentAlignment::class, var: $contentAlignment);
        $this->autofit = $this->arrayToObject(class: Autofit::class, var: $autofit);
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

    public function stringToEnum(string $enum, mixed $var): mixed
    {
        if (is_string($var)) {
            return $enum::from($var);
        }
        return $var;
    }
}
