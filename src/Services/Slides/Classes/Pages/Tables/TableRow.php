<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Tables;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Slides\Classes\Dimension;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/tables#tablerow
 * @param Dimension|array $rowHeight
 * @param TableRowProperties|array $tableRowProperties
 * @param TableCell[] $tableCells
 * @return TableRow
 */
class TableRow implements Jsonable
{
    public Dimension|array $rowHeight;
    public TableRowProperties|array $tableRowProperties;
    public array $tableCells;
    
    public function __construct(
        Dimension|array $rowHeight,
        TableRowProperties|array $tableRowProperties,
        array $tableCells
    ) {
        $this->rowHeight = $this->arrayToObject(class: Dimension::class, var: $rowHeight);
        $this->tableRowProperties = $this->arrayToObject(class: TableRowProperties::class, var: $tableRowProperties);
        // Format Table Cell
        $formattedTableCells = [];
        if ($tableCells) {
            foreach ($tableCells as $tableCell) {
                if (!($tableCell instanceof TableCell)) {
                    $formattedTableCells[] = new TableCell(...$tableCell);
                } else {
                    $formattedTableCells[] = $tableCell;
                }
            }
        }
        $this->tableCells = $formattedTableCells;
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
