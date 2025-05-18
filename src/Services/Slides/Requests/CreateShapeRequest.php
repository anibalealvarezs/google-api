<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Requests;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Presentations\Request\PageElementProperties;
use Anibalealvarezs\GoogleApi\Services\Slides\Enums\Pages\Shapes\Type;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#createshaperequest
 */
class CreateShapeRequest implements Jsonable
{
    public PageElementProperties|array $elementProperties;
    public Type|string $shapeType;
    public ?string $objectId;
    
    public function __construct(
        PageElementProperties|array $elementProperties,
        Type|string $shapeType = Type::RECTANGLE,
        ?string $objectId = null,
    ) {
        $this->elementProperties = $this->arrayToObject(class: PageElementProperties::class, var: $elementProperties);
        $this->shapeType = $this->stringToEnum(enum: Type::class, var: $shapeType);
        $this->objectId = $objectId;
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
