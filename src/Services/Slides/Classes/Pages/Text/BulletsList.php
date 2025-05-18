<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Text;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/text#list
 * @param string $listId
 * @param NestingLevel[] $nestingLevel
 * @return BulletsList
 */
class BulletsList implements Jsonable
{
    public string $listId;
    public array $nestingLevel; // Keyed NestingLevel elements by nesting level (from 0 to 8): https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/text#Page.NestingLevel
    
    public function __construct(
        string $listId,
        array $nestingLevel = [],
    ) {
        $this->listId = $listId;
        // Format Nesting Level
        $formattedNestingLevel = [];
        if ($nestingLevel) {
            foreach ($nestingLevel as $key => $value) {
                if (!is_int($key)) {
                    throw new \Exception('Nesting Level keys must be integers.');
                }
                if (!($value instanceof NestingLevel)) {
                    $formattedNestingLevel[$key] = new NestingLevel(...$value);
                } else {
                    $formattedNestingLevel[$key] = $value;
                }
            }
        }
        $this->nestingLevel = $formattedNestingLevel;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
