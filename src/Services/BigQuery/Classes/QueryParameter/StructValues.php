<?php

namespace Anibalealvarezs\GoogleApi\Services\BigQuery\Classes\QueryParameter;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @param QueryParameterValue[]|array $values
 * @return StructValues
 */
class StructValues implements Jsonable
{
    public array $values;

    public function __construct(
        array $values,
    ) {
        // Format StructValues
        $formattedValues = [];
        if ($values) {
            foreach ($values as $key => $value) {
                if (!($value instanceof QueryParameterValue)) {
                    $formattedValues[$key] = new QueryParameterValue(...$value);
                } else {
                    $formattedValues[$key] = $value;
                }
            }
        }
        $this->$values = $formattedValues;
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
