<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Other;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Enums\Other\HorizontalAlign;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#horizontalalign
 */
class TextPosition implements Jsonable
{
    public HorizontalAlign|string $horizontalAlignment;
    
    public function __construct(
        HorizontalAlign|string $horizontalAlignment = HorizontalAlign::CENTER
    ) {
        $this->horizontalAlignment = $this->stringToEnum(enum: HorizontalAlign::class, var: $horizontalAlignment);
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }

    public function stringToEnum(string $enum, mixed $var): mixed
    {
        if (is_string($var)) {
            return $enum::from($var);
        }
        return $var;
    }
}
