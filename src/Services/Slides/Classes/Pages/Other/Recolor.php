<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages\Other;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Enums\Pages\Other\Name;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/other#Page.Recolor
 */
class Recolor implements Jsonable
{
    public ColorStop|array $recolorStops;
    public Name|string $name;
    
    public function __construct(
        ColorStop|array $recolorStops,
        Name|string $name = Name::NONE
    ) {
        $this->recolorStops = $this->arrayToObject(class: ColorStop::class, var: $recolorStops);
        $this->name = $this->stringToEnum(enum: Name::class, var: $name);
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
