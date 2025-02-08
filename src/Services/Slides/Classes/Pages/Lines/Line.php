<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages\Lines;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Enums\Pages\Lines\LineCategory;
use Chmw\GoogleApi\Services\Slides\Enums\Pages\Lines\Type;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/lines#line
 */
class Line implements Jsonable
{
    public LineProperties|array $lineProperties;
    public Type|string $lineType;
    public LineCategory|string $lineCategory;

    public function __construct(
        LineProperties|array $lineProperties,
        Type|string $lineType = Type::TYPE_UNSPECIFIED,
        LineCategory|string $lineCategory = LineCategory::STRAIGHT,
    ) {
        $this->lineProperties = $this->arrayToObject(class: LineProperties::class, var: $lineProperties);
        $this->lineType = $this->stringToEnum(enum: Type::class, var: $lineType);
        $this->lineCategory = $this->stringToEnum(enum: LineCategory::class, var: $lineCategory);
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
