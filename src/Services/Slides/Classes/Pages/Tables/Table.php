<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Tables;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/tables#table
 * @param int $rows
 * @param int $columns
 * @param TableRow[] $tableRows
 * @param TableColumnProperties[] $tableColumns
 * @param TableBorderRow[] $horizontalBorderRows
 * @param TableBorderRow[] $verticalBorderRows
 * @return Table
 */
class Table implements Jsonable
{
    public int $rows;
    public int $columns;
    public array $tableRows;
    public array $tableColumns;
    public array $horizontalBorderRows;
    public array $verticalBorderRows;
    
    public function __construct(
        int $rows = 1,
        int $columns = 1,
        array $tableRows = [],
        array $tableColumns = [],
        array $horizontalBorderRows = [],
        array $verticalBorderRows = [],
    ) {
        $this->rows = $rows;
        $this->columns = $columns;
        // Format Table Rows
        $formattedTableRows = [];
        if ($tableRows) {
            foreach ($tableRows as $tableRow) {
                if (!($tableRow instanceof TableRow)) {
                    $formattedTableRows[] = new TableRow(...$tableRow);
                } else {
                    $formattedTableRows[] = $tableRow;
                }
            }
        }
        $this->tableRows = $formattedTableRows;
        // Format Table Columns
        $formattedTableColumns = [];
        if ($tableColumns) {
            foreach ($tableColumns as $tableColumn) {
                if (!($tableColumn instanceof TableColumnProperties)) {
                    $formattedTableColumns[] = new TableColumnProperties(...$tableColumn);
                } else {
                    $formattedTableColumns[] = $tableColumn;
                }
            }
        }
        $this->tableColumns = $formattedTableColumns;
        // Format Horizontal Border Rows
        $formattedHorizontalBorderRows = [];
        if ($horizontalBorderRows) {
            foreach ($horizontalBorderRows as $horizontalBorderRow) {
                if (!($horizontalBorderRow instanceof TableBorderRow)) {
                    $formattedHorizontalBorderRows[] = new TableBorderRow(...$horizontalBorderRow);
                } else {
                    $formattedHorizontalBorderRows[] = $horizontalBorderRow;
                }
            }
        }
        $this->horizontalBorderRows = $formattedHorizontalBorderRows;
        // Format Vertical Border Rows
        $formattedVerticalBorderRows = [];
        if ($verticalBorderRows) {
            foreach ($verticalBorderRows as $verticalBorderRow) {
                if (!($verticalBorderRow instanceof TableBorderRow)) {
                    $formattedVerticalBorderRows[] = new TableBorderRow(...$verticalBorderRow);
                } else {
                    $formattedVerticalBorderRows[] = $verticalBorderRow;
                }
            }
        }
        $this->verticalBorderRows = $formattedVerticalBorderRows;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
