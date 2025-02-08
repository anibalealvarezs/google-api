<?php

namespace Chmw\GoogleApi\Services\BigQuery\Classes;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\BigQuery\Classes\QueryParameter\QueryParameterType;
use Chmw\GoogleApi\Services\BigQuery\Classes\QueryParameter\QueryParameterValue;

/**
 * @see https://cloud.google.com/bigquery/docs/reference/rest/v2/QueryParameter
 * @param QueryParameterType|array $parameterType
 * @param QueryParameterValue|array $parameterValue
 * @param string|null $name
 * @return QueryParameter
 */
class QueryParameter implements Jsonable
{
    public ?string $name;
    public QueryParameterType|array $parameterType;
    public QueryParameterValue|array $parameterValue;

    public function __construct(
        QueryParameterType $parameterType,
        QueryParameterValue $parameterValue,
        ?string $name = null,
    ) {
        $this->parameterType = $this->arrayToObject(class: QueryParameterType::class, var: $parameterType);
        $this->parameterValue = $this->arrayToObject(class: QueryParameterValue::class, var: $parameterValue);
        $this->name = $name;
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
