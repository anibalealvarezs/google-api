<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages\Other;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Enums\Pages\Other\PlaceholderType;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/other#Page.Placeholder
 */
class Placeholder implements Jsonable
{
    public ?int $index;
    public ?string $parentObjectId;
    public PlaceholderType|string $type;
    
    public function __construct(
        ?int $index = null,
        ?string $parentObjectId = null,
        PlaceholderType|string $type = PlaceholderType::BODY,
    ) {
        $this->index = $index;
        $this->parentObjectId = $parentObjectId;
        $this->type = $this->stringToEnum(enum: PlaceholderType::class, var: $type);
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }

    public function stringToEnum(string $enum, mixed $var): mixed
    {
        if (is_string($var)) {
            return $enum::from($var);
        }
        return $var;
    }
}
