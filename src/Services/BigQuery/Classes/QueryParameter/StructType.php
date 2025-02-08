<?php

namespace Chmw\GoogleApi\Services\BigQuery\Classes\QueryParameter;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @param QueryParameterType|array $type
 * @param string|null $name
 * @param string|null $description
 * @return StructType
 */
class StructType implements Jsonable
{
    public QueryParameterType|array $type;
    public ?string $name;
    public ?string $description;

    public function __construct(
        QueryParameterType|array $type,
        ?string $name = null,
        ?string $description = null,
    ) {
        $this->type = $this->arrayToObject(class: QueryParameterType::class, var: $type);
        $this->name = $name;
        $this->description = $description;
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
