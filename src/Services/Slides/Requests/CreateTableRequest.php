<?php

namespace Chmw\GoogleApi\Services\Slides\Requests;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Presentations\Request\PageElementProperties;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#createsliderequest
 */
class CreateTableRequest implements Jsonable
{
    public PageElementProperties|array $elementProperties;
    public int $rows;
    public int $columns;
    public string $objectId;
    
    public function __construct(
        PageElementProperties|array $elementProperties,
        int $rows,
        int $columns,
        ?string $objectId = null,
    ) {
        $this->elementProperties = $this->arrayToObject(class: PageElementProperties::class, var: $elementProperties);
        $this->rows = $rows;
        $this->columns = $columns;
        $this->objectId = $objectId;
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
