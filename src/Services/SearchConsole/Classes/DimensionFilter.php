<?php

namespace Anibalealvarezs\GoogleApi\Services\SearchConsole\Classes;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\SearchConsole\Enums\Dimension;
use Anibalealvarezs\GoogleApi\Services\SearchConsole\Enums\Operator;

/**
 * @see https://developers.google.com/webmaster-tools/v1/searchanalytics/query#dimensionFilterGroups.filters
 */
class DimensionFilter implements Jsonable
{
    public Dimension|string $dimension;
    public string $expression;
    public Operator|string $operator;

    public function __construct(
        string $expression,
        Dimension|string $dimension = Dimension::QUERY,
        Operator|string $operator = Operator::CONTAINS,
    ) {
        $this->expression = $expression;
        $this->dimension = $this->stringToEnum(enum: Dimension::class, var: $dimension);
        $this->operator = $this->stringToEnum(enum: Operator::class, var: $operator);
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

    public function arrayToObject(string $class, mixed $var): mixed
    {
        if (is_array($var)) {
            return new $class(...$var);
        }
        return $var;
    }
}