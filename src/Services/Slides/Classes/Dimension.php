<?php

namespace Chmw\GoogleApi\Services\Slides\Classes;

use Chmw\GoogleApi\Services\Slides\Enums\Unit;
use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/Dimension
 */
class Dimension implements Jsonable
{
    public ?float $magnitude;
    public Unit|string $unit;

    public function __construct(
        ?float $magnitude = null,
        Unit|string $unit = Unit::EMU
    ) {
        $this->magnitude = $magnitude;
        $this->unit = $this->stringToEnum(enum: Unit::class, var: $unit);
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }

    public function stringToEnum(string $enum, mixed $var): mixed
    {
        if (is_string($var)) {
            return $enum::from($var);
        }
        return $var;
    }
}
