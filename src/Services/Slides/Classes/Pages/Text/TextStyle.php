<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages\Text;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Dimension;
use Chmw\GoogleApi\Services\Slides\Classes\Pages\Other\Link;
use Chmw\GoogleApi\Services\Slides\Enums\Pages\Text\BaselineOffset;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/text#textstyle
 */
class TextStyle implements Jsonable
{
    public OptionalColor|array|null $backgroundColor;
    public OptionalColor|array|null $foregroundColor;
    public bool $bold;
    public bool $italic;
    public string $fontFamily;
    public Dimension|array|null $fontSize;
    public Link|array|null $link;
    public BaselineOffset|string $baselineOffset;
    public bool $smallCaps;
    public bool $strikethrough;
    public bool $underline;
    public WeightedFontFamily|array|null $weightedFontFamily;
    
    public function __construct(
        OptionalColor|array|null $backgroundColor = null,
        OptionalColor|array|null $foregroundColor = null,
        bool $bold = false,
        bool $italic = false,
        string $fontFamily = 'Arial',
        Dimension|array|null $fontSize = null,
        Link|array|null $link = null,
        BaselineOffset|string $baselineOffset = BaselineOffset::BASELINE_OFFSET_UNSPECIFIED,
        bool $smallCaps = false,
        bool $strikethrough = false,
        bool $underline = false,
        WeightedFontFamily|array|null $weightedFontFamily = null,
    ) {
        $this->backgroundColor = $this->arrayToObject(class: OptionalColor::class, var: $backgroundColor);
        $this->foregroundColor = $this->arrayToObject(class: OptionalColor::class, var: $foregroundColor);
        $this->bold = $bold;
        $this->italic = $italic;
        $this->fontFamily = $fontFamily;
        $this->fontSize = $this->arrayToObject(class: Dimension::class, var: $fontSize);
        $this->link = $this->arrayToObject(class: Link::class, var: $link);
        $this->baselineOffset = $this->stringToEnum(enum: BaselineOffset::class, var: $baselineOffset);
        $this->smallCaps = $smallCaps;
        $this->strikethrough = $strikethrough;
        $this->underline = $underline;
        $this->weightedFontFamily = $this->arrayToObject(class: WeightedFontFamily::class, var: $weightedFontFamily);
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
