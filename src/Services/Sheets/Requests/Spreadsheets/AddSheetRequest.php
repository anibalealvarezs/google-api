<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Requests\Spreadsheets;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Sheets\SheetProperties;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/request#addsheetrequest
 */
class AddSheetRequest implements Jsonable
{
    public SheetProperties|array $properties;
    
    public function __construct(
        SheetProperties|array $properties
    ) {
        $this->properties = $this->arrayToObject(class: SheetProperties::class, var: $properties);
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
