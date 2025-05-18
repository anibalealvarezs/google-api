<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Other;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Dimension;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Other\OutlineFill;
use Anibalealvarezs\GoogleApi\Services\Slides\Enums\Pages\Other\DashStyle;
use Anibalealvarezs\GoogleApi\Services\Slides\Enums\Pages\Other\PropertyState;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/other#Page.Outline
 */
class Outline implements Jsonable
{
    public Dimension|array $weight;
    public OutlineFill|array|null $outlineFill;
    public DashStyle|string $dashStyle;
    public PropertyState|string $propertyState;
    
    public function __construct(
        Dimension|array $weight,
        OutlineFill|array|null $outlineFill = null,
        DashStyle|string $dashStyle = "SOLID",
        PropertyState|string $propertyState = PropertyState::RENDERED
    ) {
        $this->outlineFill = $this->arrayToObject(class: OutlineFill::class, var: $outlineFill);
        $this->weight = $this->arrayToObject(class: Dimension::class, var: $weight);
        $this->dashStyle = $this->stringToEnum(enum: DashStyle::class, var: $dashStyle);
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
