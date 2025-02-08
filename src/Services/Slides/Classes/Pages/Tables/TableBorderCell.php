<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages\Tables;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/tables#tablebordercell
 */
class TableBorderCell implements Jsonable
{
    public TableCellLocation|array $location;
    public TableBorderProperties|array $tableBorderProperties;
    
    public function __construct(
        TableCellLocation|array $location,
        TableBorderProperties|array $tableBorderProperties
    ) {
        $this->location = $this->arrayToObject(class: TableCellLocation::class, var: $location);
        $this->tableBorderProperties = $this->arrayToObject(class: TableBorderProperties::class, var: $tableBorderProperties);
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
