<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Requests;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Presentations\Request\PageElementProperties;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#createimagerequest
 */
class CreateImageRequest implements Jsonable
{
    public PageElementProperties|array $elementProperties;
    public string $url;
    public ?string $objectId;
    
    public function __construct(
        PageElementProperties|array $elementProperties,
        string $url,
        ?string $objectId = null,
    ) {
        $this->elementProperties = $this->arrayToObject(class: PageElementProperties::class, var: $elementProperties);
        $this->url = $url;
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
