<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages\Other;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Pages\Other\OpaqueColor;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/other#Page.SolidFill
 */
class SolidFill implements Jsonable
{
    public OpaqueColor|array $color;
    public float $alpha;
    
    public function __construct(
        OpaqueColor|array $color,
        float $alpha = 1.0
    ) {
        $this->color = $this->arrayToObject(class: OpaqueColor::class, var: $color);
        $this->alpha = $alpha;
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
