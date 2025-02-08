<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages\Other;

use Chmw\GoogleApi\Services\Slides\Enums\Unit;
use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/other#Page.AffineTransform
 */
class AffineTransform implements Jsonable
{
    public float $scaleX;
    public float $scaleY;
    public ?float $shearX;
    public ?float $shearY;
    public ?float $translateX;
    public ?float $translateY;
    public Unit|string $unit;
    
    public function __construct(
        float $scaleX = 1,
        float $scaleY = 1,
        ?float $shearX = null,
        ?float $shearY = null,
        ?float $translateX = null,
        ?float $translateY = null,
        Unit|string $unit = Unit::EMU
    ) {
        $this->scaleX = $scaleX;
        $this->scaleY = $scaleY;
        $this->shearX = $shearX;
        $this->shearY = $shearY;
        $this->translateX = $translateX;
        $this->translateY = $translateY;
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
