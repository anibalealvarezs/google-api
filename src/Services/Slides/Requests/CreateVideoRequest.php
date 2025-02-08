<?php

namespace Chmw\GoogleApi\Services\Slides\Requests;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Presentations\Request\PageElementProperties;
use Chmw\GoogleApi\Services\Slides\Enums\Pages\Videos\Source;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#createvideorequest
 */
class CreateVideoRequest implements Jsonable
{
    public PageElementProperties|array $elementProperties;
    public string $id;
    public Source|string $source;
    public string $objectId;
    
    public function __construct(
        PageElementProperties|array $elementProperties,
        string $id,
        Source|string $source = Source::DRIVE,
        ?string $objectId = null,
    ) {
        $this->elementProperties = $this->arrayToObject(class: PageElementProperties::class, var: $elementProperties);
        $this->id = $id;
        $this->source = $this->stringToEnum(enum: Source::class, var: $source);
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

    public function stringToEnum(string $enum, mixed $var): mixed
    {
        if (is_string($var)) {
            return $enum::from($var);
        }
        return $var;
    }
}
