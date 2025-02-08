<?php

namespace Chmw\GoogleApi\Services\Slides\Classes\Pages\Tables;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Slides\Classes\Dimension;
use Chmw\GoogleApi\Services\Slides\Classes\Pages\Text\TextContent;
use Chmw\GoogleApi\Services\Slides\Enums\Pages\Other\DashStyle;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/tables#tableborderproperties
 */
class TableBorderProperties implements Jsonable
{
    public TableBorderFill|array $tableBorderFill;
    public Dimension|array $weight;
    public DashStyle|string $dashStyle;
    
    public function __construct(
        TableBorderFill|array $tableBorderFill,
        Dimension|array $weight,
        DashStyle|string $dashStyle = DashStyle::SOLID,
    ) {
        $this->tableBorderFill = $this->arrayToObject(class: TableBorderFill::class, var: $tableBorderFill);
        $this->weight = $this->arrayToObject(class: Dimension::class, var: $weight);
        $this->dashStyle = $this->stringToEnum(enum: DashStyle::class, var: $dashStyle);
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
