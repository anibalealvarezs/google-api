<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages\Other;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Dimension;
use Chmw\GoogleApi\Services\Slides\Enums\Pages\Other\PropertyState;
use Chmw\GoogleApi\Services\Slides\Enums\Pages\Other\RectanglePosition;
use Chmw\GoogleApi\Services\Slides\Enums\Pages\Other\ShadowType;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/other#Page.Shadow
 */
class Shadow implements Jsonable
{
    public AffineTransform|array|null $transform;
    public Dimension|array|null $blurRadius;
    public OpaqueColor|array|null $color;
    public readonly RectanglePosition|string $alignment;
    public ShadowType|string $type;
    public float $alpha;
    public readonly bool $rotateWithShape;
    public PropertyState|string $propertyState;
    
    public function __construct(
        AffineTransform|array|null $transform = null,
        Dimension|array|null $blurRadius = null,
        OpaqueColor|array|null $color = null,
        RectanglePosition|string $alignment = RectanglePosition::CENTER,
        ShadowType|string $type = ShadowType::OUTER,
        float $alpha = 1.0,
        bool $rotateWithShape = true,
        PropertyState|string $propertyState = PropertyState::RENDERED,
    ) {
        $this->blurRadius = $this->arrayToObject(class: Dimension::class, var: $blurRadius);
        $this->color = $this->arrayToObject(class: OpaqueColor::class, var: $color);
        $this->transform = $this->arrayToObject(class: AffineTransform::class, var: $transform);
        $this->alignment = $this->stringToEnum(enum: RectanglePosition::class, var: $alignment);
        $this->type = $this->stringToEnum(enum: ShadowType::class, var: $type);
        $this->alpha = $alpha;
        $this->rotateWithShape = $rotateWithShape;
        $this->propertyState = $this->stringToEnum(enum: PropertyState::class, var: $propertyState);
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
