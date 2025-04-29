<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Other;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/other#Page.ColorStop
 */
class ColorStop implements Jsonable
{
    public OpaqueColor|array $color;
    public float $alpha;
    public float $position;
    
    public function __construct(
        OpaqueColor|array $color,
        float $alpha = 1.0,
        float $position = 1.0
    ) {
        $this->color = $this->arrayToObject(class: OpaqueColor::class, var: $color);
        $this->alpha = $alpha;
        $this->position = $position;
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
