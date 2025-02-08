<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Charts;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Enums\Charts\PointShape;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/charts#pointstyle
 */
class PointStyle implements Jsonable
{
    public float $size;
    public PointShape|string $shape;
    
    public function __construct(
        float $size,
        PointShape|string $shape = PointShape::POINT_SHAPE_UNSPECIFIED,
    ) {
        $this->size = $size;
        $this->shape = $this->stringToEnum(enum: PointShape::class, var: $shape);
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
