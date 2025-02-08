<?php

namespace Chmw\GoogleApi\Services\Slides\Requests;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Pages\Videos\VideoProperties;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#UpdateVideoPropertiesRequest
 */
class UpdateVideoPropertiesRequest implements Jsonable
{
    public string $objectId;
    public VideoProperties|array $videoProperties;
    public string $fields;
    
    public function __construct(
        string $objectId,
        VideoProperties|array $videoProperties,
        string $fields = "*"
    ) {
        $this->objectId = $objectId;
        $this->videoProperties = $this->arrayToObject(class: VideoProperties::class, var: $videoProperties); 
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
