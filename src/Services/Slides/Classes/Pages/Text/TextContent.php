<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Text;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/text#textcontent
 * @param TextElement[] $textElements
 * @param BulletsList[] $lists
 * @return TextContent
 */
class TextContent implements Jsonable
{
    public array $textElements;
    public array $lists; // Keyed List elements by list ID: https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/text#Page.List
    
    public function __construct(
        array $textElements,
        array $lists = [],
    ) {
        // Format Text Elements
        $formattedTextElements = [];
        if ($textElements) {
            foreach ($textElements as $textElement) {
                if (!($textElement instanceof TextElement)) {
                    $formattedTextElements[] = new TextElement(...$textElement);
                } else {
                    $formattedTextElements[] = $textElement;
                }
            }
        }
        $this->textElements = $formattedTextElements;
        // Format Lists
        $formattedLists = [];
        if ($lists) {
            foreach ($lists as $key => $value) {
                if (!($value instanceof BulletsList)) {
                    $formattedLists[(string)$key] = new BulletsList(...$value);
                } else {
                    $formattedLists[(string)$key] = $value;
                }
            }
        }
        $this->lists = $formattedLists;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
