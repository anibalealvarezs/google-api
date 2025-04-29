<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Cells;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\TextFormat;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/cells#textformatrun
 */
class TextFormatRun implements Jsonable
{
    public int $startIndex;
    public TextFormat|array|null $format;
    
    public function __construct(
        int $startIndex,
        TextFormat|array|null $format = null,
    ) {
        $this->startIndex = $startIndex;
        $this->format = $this->arrayToObject(class: TextFormat::class, var: $format);
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
