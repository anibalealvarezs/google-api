<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Presentations\Request;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Tables\TableCellLocation;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations/request#TableRange
 */
class TableRange implements Jsonable
{
    public TableCellLocation|array $location;
    public int $rowSpan;
    public int $columnSpan;
    
    public function __construct(
        TableCellLocation|array $location,
        int $rowSpan = 1,
        int $columnSpan = 1
    ) {
        $this->location = $this->arrayToObject(class: TableCellLocation::class, var: $location);
        $this->rowSpan = $rowSpan;
        $this->columnSpan = $columnSpan;
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
