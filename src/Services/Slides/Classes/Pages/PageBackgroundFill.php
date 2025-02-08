<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Pages\Other\SolidFill;
use Chmw\GoogleApi\Services\Slides\Enums\Pages\Other\PropertyState;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages#pagebackgroundfill
 */
class PageBackgroundFill implements Jsonable
{
    public SolidFill|array|null $solidFill;
    public StretchedPictureFill|array|null $stretchedPictureFill;
    public PropertyState|string $propertyState;
    
    public function __construct(
        SolidFill|array|null $solidFill = null,
        StretchedPictureFill|array|null $stretchedPictureFill = null,
        PropertyState|string $propertyState = PropertyState::INHERIT,
    ) {
        $this->solidFill = $this->arrayToObject(class: SolidFill::class, var: $solidFill);
        $this->stretchedPictureFill = $this->arrayToObject(class: StretchedPictureFill::class, var: $stretchedPictureFill);
        $this->propertyState = $this->stringToEnum(enum: PropertyState::class, var: $propertyState);
        
        $this->keepOneOfKind([
            'solidFill',
            'stretchedPictureFill'
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

    public function stringToEnum(string $enum, mixed $var): mixed
    {
        if (is_string($var)) {
            return $enum::from($var);
        }
        return $var;
    }
}
