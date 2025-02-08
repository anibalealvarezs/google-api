<?php

namespace Chmw\GoogleApi\Services\Sheets\Requests\Sheets;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Sheets\SheetProperties;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/request#updatesheetpropertiesrequest
 */
class UpdateSheetPropertiesRequest implements Jsonable
{
    public SheetProperties $properties;
    public string $fields;
    
    public function __construct(
        SheetProperties $properties,
        string $fields = '*',
    ) {
        $this->properties = $this->arrayToObject(class: SheetProperties::class, var: $properties);
        $this->fields = $fields;
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
