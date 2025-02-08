<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages\Text;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Enums\Pages\Text\Type;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/text#autotext
 */
class AutoText implements Jsonable
{
    public string $content;
    public TextStyle|array $style;
    public Type|string $type;
    
    public function __construct(
        string $content,
        TextStyle|array $style,
        Type|string $type = Type::TYPE_UNSPECIFIED,
    ) {
        $this->content = $content;
        $this->style = $this->arrayToObject(class: TextStyle::class, var: $style);
        $this->type = $this->stringToEnum(enum: Type::class, var: $type);
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
