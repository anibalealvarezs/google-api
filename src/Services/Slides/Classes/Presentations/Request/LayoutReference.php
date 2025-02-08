<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Presentations\Request;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Google\Interfaces\Kindable;
use Chmw\GoogleApi\Services\Slides\Enums\Presentations\Request\PredefinedLayout;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#LayoutReference
 */
class LayoutReference implements Jsonable, Kindable
{
    public ?string $layoutId;
    public PredefinedLayout|string $predefinedLayout;
    
    public function __construct(
        ?string $layoutId = null,
        PredefinedLayout|string $predefinedLayout = PredefinedLayout::BLANK
    ) {
        $this->layoutId = $layoutId;
        $this->predefinedLayout = $this->stringToEnum(enum: PredefinedLayout::class, var: $predefinedLayout);

        $this->keepOneOfKind([
            'layoutId',
            'predefinedLayout'
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

    public function stringToEnum(string $enum, mixed $var): mixed
    {
        if (is_string($var)) {
            return $enum::from($var);
        }
        return $var;
    }
}
