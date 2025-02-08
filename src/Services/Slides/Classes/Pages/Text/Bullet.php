<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages\Text;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/text#bullet
 */
class Bullet implements Jsonable
{
    public string $listId;
    public TextStyle|array $bulletStyle;
    public ?string $glyph;
    public ?int $nestingLevel;
    
    public function __construct(
        string $listId,
        TextStyle|array $bulletStyle,
        ?string $glyph = null,
        ?int $nestingLevel = null,
    ) {
        $this->listId = $listId;
        $this->glyph = $glyph;
        $this->bulletStyle = $this->arrayToObject(class: TextStyle::class, var: $bulletStyle);
        $this->nestingLevel = $nestingLevel;
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
