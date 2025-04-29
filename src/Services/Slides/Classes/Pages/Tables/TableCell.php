<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Tables;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Text\TextContent;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/tables#tablecell
 */
class TableCell implements Jsonable
{
    public TableCellLocation|array $location;
    public int $rowSpan;
    public int $columnSpan;
    public TextContent|array $text;
    public TableCellProperties|array $tableCellProperties;
    
    public function __construct(
        TableCellLocation|array $location,
        int $rowSpan,
        int $columnSpan,
        TextContent|array $text,
        TableCellProperties|array $tableCellProperties
    ) {
        $this->location = $this->arrayToObject(class: TableCellLocation::class, var: $location);
        $this->rowSpan = $rowSpan;
        $this->columnSpan = $columnSpan;
        $this->text = $this->arrayToObject(class: TextContent::class, var: $text);
        $this->tableCellProperties = $this->arrayToObject(class: TableCellProperties::class, var: $tableCellProperties);
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
