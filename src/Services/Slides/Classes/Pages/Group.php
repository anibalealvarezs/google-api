<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages#group
 * @param PageElement[] $children
 * @return Group
 */
class Group implements Jsonable
{
    public array $children;
    
    public function __construct(
        array $children,
    ) {
        // Format Children
        $formattedChildren = [];
        if ($children) {
            foreach ($children as $child) {
                if (!($child instanceof PageElement)) {
                    $formattedChildren[] = new PageElement(...$child);
                } else {
                    $formattedChildren[] = $child;
                }
            }
        }
        $this->children = $formattedChildren;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
