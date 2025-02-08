<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages\Shapes;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Pages\Other\Placeholder;
use Chmw\GoogleApi\Services\Slides\Classes\Pages\Text\TextContent;
use Chmw\GoogleApi\Services\Slides\Enums\Pages\Shapes\Type;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/shapes#shape
 */
class Shape implements Jsonable
{
    public TextContent|array $text;
    public ShapeProperties|array $shapeProperties;
    public Placeholder|array|null $placeholder;
    public Type|string $shapeType;

    public function __construct(
        TextContent|array $text,
        ShapeProperties|array $shapeProperties,
        Placeholder|array|null $placeholder = null,
        Type|string $shapeType = Type::TYPE_UNSPECIFIED,
    ) {
        $this->text = $this->arrayToObject(class: TextContent::class, var: $text);
        $this->shapeProperties = $this->arrayToObject(class: ShapeProperties::class, var: $shapeProperties);
        $this->placeholder = $this->arrayToObject(class: Placeholder::class, var: $placeholder);
        $this->shapeType = $this->stringToEnum(enum: Type::class, var: $shapeType);
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
