<?php

namespace Chmw\GoogleApi\Services\BigQuery\Classes\QueryParameter;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://cloud.google.com/bigquery/docs/reference/rest/v2/QueryParameter#queryparametervalue
 * @param StructValues|array $structValues
 * @param QueryParameterValue[]|array|null $arrayValues
 * @param string|null $value
 * @return QueryParameterValue
 */
class QueryParameterValue implements Jsonable
{
    public StructValues|array $structValues; // Values most be an array of key/value pairs under the main key "values"
    public array $arrayValues;
    public string $value;
    
    public function __construct(
        array $structValues,
        ?array $arrayValues,
        ?string $value = null,
    ) {
        // The final array will not include the main key "values"
        $this->structValues = $this->arrayToObject(class: StructValues::class, var: $structValues)->values;
        // Format StructValues
        $formattedArrayValues = [];
        if ($arrayValues) {
            foreach ($arrayValues as $arrayValue) {
                if (!($arrayValue instanceof QueryParameterValue)) {
                    $formattedArrayValues[] = new QueryParameterValue(...$arrayValue);
                } else {
                    $formattedArrayValues[] = $arrayValue;
                }
            }
        }
        $this->arrayValues = $formattedArrayValues;
        $this->value = $value;
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
