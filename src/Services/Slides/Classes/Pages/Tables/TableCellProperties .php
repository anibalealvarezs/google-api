<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages\Tables;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Enums\Pages\Other\ContentAlignment;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/tables#Page.TableCellProperties
 */
class TableCellProperties implements Jsonable
{
    public TableCellBackgroundFill|array $tableCellBackgroundFill;
    public ContentAlignment|string $contentAlignment;
    
    public function __construct(
        TableCellBackgroundFill|array $tableCellBackgroundFill,
        ContentAlignment|string $contentAlignment
    ) {
        $this->location = $this->arrayToObject(class: TableCellBackgroundFill::class, var: $tableCellBackgroundFill);
        $this->rowSpan = $this->stringToEnum(enum: ContentAlignment::class, var: $contentAlignment);
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
