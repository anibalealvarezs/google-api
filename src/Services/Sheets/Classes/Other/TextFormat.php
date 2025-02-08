<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Other;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#textformat
 */
class TextFormat implements Jsonable
{
    public ColorStyle|array $foregroundColorStyle;
    public string $fontFamily;
    public int $fontSize;
    public bool $bold;
    public bool $italic;
    public ?bool $strikethrough;
    public ?bool $underline;
    public Link|array|null $link;
    
    public function __construct(
        ColorStyle|array $foregroundColorStyle,
        string $fontFamily = 'Roboto',
        int $fontSize = 12,
        bool $bold = false,
        bool $italic = false,
        ?bool $strikethrough = false,
        ?bool $underline = false,
        Link|array|null $link = null,
        mixed $foregroundColor = null,
    ) {
        $this->foregroundColorStyle = $this->arrayToObject(class: ColorStyle::class, var: $foregroundColorStyle);
        $this->fontFamily = $fontFamily;
        $this->fontSize = $fontSize;
        $this->bold = $bold;
        $this->italic = $italic;
        $this->strikethrough = $strikethrough;
        $this->underline = $underline;
        $this->link = $this->arrayToObject(class: Link::class, var: $link);
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
