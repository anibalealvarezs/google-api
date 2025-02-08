<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Presentations\Request;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Enums\Presentations\Request\RangeType;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#Range
 */
class Range implements Jsonable
{
    public ?int $startIndex;
    public ?int $endIndex;
    public RangeType|string $type;
    
    public function __construct(
        ?int $startIndex = null,
        ?int $endIndex = null,
        RangeType|string $type = RangeType::ALL
    ) {
        $this->startIndex = $startIndex;
        $this->endIndex = $endIndex;
        $this->type = $this->stringToEnum(enum: RangeType::class, var: $type);
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
