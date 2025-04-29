<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Text;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/text#textrun
 */
class TextRun implements Jsonable
{
    public string $content;
    public TextStyle|array $style;
    
    public function __construct(
        string $content,
        TextStyle|array $style,
    ) {
        $this->content = $content;
        $this->style = $this->arrayToObject(class: TextStyle::class, var: $style);
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
