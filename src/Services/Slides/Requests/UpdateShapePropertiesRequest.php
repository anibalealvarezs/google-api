<?php

namespace Chmw\GoogleApi\Services\Slides\Requests;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Pages\Shapes\ShapeProperties;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#UpdateShapePropertiesRequest
 */
class UpdateShapePropertiesRequest implements Jsonable
{
    public string $objectId;
    public ShapeProperties|array $shapeProperties;
    public string $fields;
    
    public function __construct(
        string $objectId,
        ShapeProperties|array $shapeProperties,
        string $fields = "*"
    ) {
        $this->objectId = $objectId;
        $this->shapeProperties = $this->arrayToObject(class: ShapeProperties::class, var: $shapeProperties);
        $this->fields = $fields;
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
