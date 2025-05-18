<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Presentations\Request;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Kindable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Other\Placeholder;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#LayoutPlaceholderIdMapping
 */
class LayoutPlaceholderIdMapping implements Jsonable, Kindable
{
    public Placeholder|array|null $layoutPlaceholder;
    public ?string $layoutPlaceholderObjectId;
    public ?string $objectId;

    public function __construct(
        Placeholder|array|null $layoutPlaceholder = null,
        ?string $layoutPlaceholderObjectId = null,
        ?string $objectId = null,
    ) {
        $this->layoutPlaceholder = $this->arrayToObject(class: Placeholder::class, var: $layoutPlaceholder);
        $this->layoutPlaceholderObjectId = $layoutPlaceholderObjectId;
        $this->objectId = $objectId;

        $this->keepOneOfKind([
            'layoutPlaceholder',
            'layoutPlaceholderObjectId'
        ]);
    }

    public function keepOneOfKind(array $properties): void
    {
        if ($key = Helpers::getFirstNotNullPropertyFrom($this, $properties)) {
            Helpers::nullifyOtherProperties($this, $key);
        }
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
