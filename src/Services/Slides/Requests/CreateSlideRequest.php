<?php

namespace Chmw\GoogleApi\Services\Slides\Requests;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Presentations\Request\LayoutPlaceholderIdMapping;
use Chmw\GoogleApi\Services\Slides\Classes\Presentations\Request\LayoutReference;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#createsliderequest
 */
class CreateSlideRequest implements Jsonable
{
    public ?string $objectId;
    public ?int $index;
    public LayoutReference|array|null $slideLayoutReference;
    public LayoutPlaceholderIdMapping|array|null $placeholderIdMappings;
    
    public function __construct(
        ?string $objectId = null,
        ?int $index = null,
        LayoutReference|array|null $slideLayoutReference = null,
        LayoutPlaceholderIdMapping|array|null $placeholderIdMappings = null,
    ) {
        $this->objectId = $objectId;
        $this->index = $index;
        $this->slideLayoutReference = $this->arrayToObject(class: LayoutReference::class, var: $slideLayoutReference);
        $this->placeholderIdMappings = $this->arrayToObject(class: LayoutPlaceholderIdMapping::class, var: $placeholderIdMappings);
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
