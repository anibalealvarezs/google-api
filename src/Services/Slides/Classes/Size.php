<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/Size
 */
class Size implements Jsonable
{
    public Dimension|array $width;
    public Dimension|array $height;
    
    public function __construct(
        Dimension|array $width,
        Dimension|array $height
    ) {
        $this->width = $this->arrayToObject(class: Dimension::class, var: $width);
        $this->height = $this->arrayToObject(class: Dimension::class, var: $height);
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
