<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages\Other;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Google\Interfaces\Kindable;
use Chmw\GoogleApi\Services\Slides\Enums\Pages\Other\RelativeSlideLink;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/other#Page.Link
 */
class Link implements Jsonable, Kindable
{
    public ?string $url;
    public RelativeSlideLink|string $relativeLink;
    public ?string $pageObjectId;
    public ?int $slideIndex;
    
    public function __construct(
        ?string $url = null,
        RelativeSlideLink|string $relativeLink = RelativeSlideLink::RELATIVE_SLIDE_LINK_UNSPECIFIED,
        ?string $pageObjectId = null,
        ?int $slideIndex = null,
    ) {
        $this->url = $url;
        $this->relativeLink = $this->stringToEnum(enum: RelativeSlideLink::class, var: $relativeLink);
        $this->pageObjectId = $pageObjectId;
        $this->slideIndex = $slideIndex;

        $this->keepOneOfKind([
            'url',
            'relativeLink',
            'pageObjectId',
            'slideIndex'
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
