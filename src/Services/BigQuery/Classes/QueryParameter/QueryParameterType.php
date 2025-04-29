<?php

namespace Anibalealvarezs\GoogleApi\Services\BigQuery\Classes\QueryParameter;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://cloud.google.com/bigquery/docs/reference/rest/v2/QueryParameter#queryparametertype
 * @param string $type
 * @param QueryParameterType|array|null $arrayType
 * @param StructType[] $structTypes
 * @return QueryParameterType
 */
class QueryParameterType implements Jsonable
{
    public string $type;
    public QueryParameterType|array|null $arrayType;
    public array $structTypes;
    
    public function __construct(
        string $type,
        QueryParameterType|array|null $arrayType = null,
        array $structTypes = [],
    ) {
        $this->type = $type;
        $this->arrayType = $this->arrayToObject(class: QueryParameterType::class, var: $arrayType);
        // Format StructTypes
        $formattedStructTypes = [];
        if ($structTypes) {
            foreach ($structTypes as $structType) {
                if (!($structType instanceof StructType)) {
                    $formattedStructTypes[] = new StructType(...$structType);
                } else {
                    $formattedStructTypes[] = $structType;
                }
            }
        }
        $this->structTypes = $formattedStructTypes;
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
