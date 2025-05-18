<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Requests;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Other\ImageProperties;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#UpdateImagePropertiesRequest
 */
class UpdateImagePropertiesRequest implements Jsonable
{
    public string $objectId;
    public ImageProperties|array $imageProperties;
    public string $fields;
    
    public function __construct(
        string $objectId,
        ImageProperties|array $imageProperties,
        string $fields = "*"
    ) {
        $this->objectId = $objectId;
        $this->imageProperties = $this->arrayToObject(class: ImageProperties::class, var: $imageProperties);
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
