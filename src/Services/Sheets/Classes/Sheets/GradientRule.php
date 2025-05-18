<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Sheets;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/sheets#gradientrule
 */
class GradientRule implements Jsonable
{
    public InterpolationPoint|array $minpoint;
    public InterpolationPoint|array $maxpoint;
    public InterpolationPoint|array|null $midpoint;
    
    public function __construct(
        InterpolationPoint|array $minpoint,
        InterpolationPoint|array $maxpoint,
        InterpolationPoint|array|null $midpoint = null,
    ) {
        $this->minpoint = $this->arrayToObject(class: InterpolationPoint::class, var: $minpoint);
        $this->maxpoint = $this->arrayToObject(class: InterpolationPoint::class, var: $maxpoint);
        $this->midpoint = $this->arrayToObject(class: InterpolationPoint::class, var: $midpoint);
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
