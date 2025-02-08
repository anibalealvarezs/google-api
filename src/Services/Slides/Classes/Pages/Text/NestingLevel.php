<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages\Text;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/text#nestinglevel
 */
class NestingLevel implements Jsonable
{
    public TextStyle|array $bulletStyle;
    
    public function __construct(
        TextStyle|array $bulletStyle,
    ) {
        $this->bulletStyle = $this->arrayToObject(class: TextStyle::class, var: $bulletStyle);
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
