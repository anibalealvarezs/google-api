<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Enums\Pages\PageType;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages#Page
 * @param string $objectId
 * @param PageProperties|array $pageProperties
 * @param PageElement[] $pageElements
 * @param LayoutProperties|array|null $layoutProperties
 * @param MasterProperties|array|null $masterProperties
 * @param NotesProperties|array|null $notesProperties
 * @param SlideProperties|array|null $slideProperties
 * @param string|null $revisionId
 * @param PageType|string $pageType
 * @return Page
 */
class Page implements Jsonable
{
    public string $objectId;
    public PageProperties|array $pageProperties;
    public array $pageElements;
    public LayoutProperties|array|null $layoutProperties;
    public MasterProperties|array|null $masterProperties;
    public NotesProperties|array|null $notesProperties;
    public SlideProperties|array|null $slideProperties;
    public ?string $revisionId;
    public PageType|string $pageType;
    
    public function __construct(
        string $objectId,
        PageProperties|array $pageProperties,
        array $pageElements,
        LayoutProperties|array|null $layoutProperties = null,
        MasterProperties|array|null $masterProperties = null,
        NotesProperties|array|null $notesProperties = null,
        SlideProperties|array|null $slideProperties = null,
        ?string $revisionId = null,
        PageType|string $pageType = PageType::SLIDE,
    ) {
        $this->objectId = $objectId;
        $this->pageProperties = $this->arrayToObject(class: PageProperties::class, var: $pageProperties);
        // Format Page Elements
        $formattedPageElements = [];
        if ($pageElements) {
            foreach ($pageElements as $pageElement) {
                if (!($pageElement instanceof PageElement)) {
                    $formattedPageElements[] = new PageElement(...$pageElement);
                } else {
                    $formattedPageElements[] = $pageElement;
                }
            }
        }
        $this->pageElements = $formattedPageElements;
        $this->layoutProperties = $this->arrayToObject(class: LayoutProperties::class, var: $layoutProperties);
        $this->notesProperties = $this->arrayToObject(class: MasterProperties::class, var: $notesProperties);
        $this->masterProperties = $this->arrayToObject(class: NotesProperties::class, var: $masterProperties);
        $this->slideProperties = $this->arrayToObject(class: SlideProperties::class, var: $slideProperties);
        $this->revisionId = $revisionId;
        $this->pageType = $this->stringToEnum(enum: PageType::class, var: $pageType);
        
        $this->keepOneOfKind([
            'slideProperties',
            'layoutProperties',
            'notesProperties',
            'masterProperties',
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
