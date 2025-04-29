<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Tables;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/tables#tableborderrow
 * @param TableBorderCell[] $tableBorderCells
 * @return TableBorderRow
 */
class TableBorderRow implements Jsonable
{
    public array $tableBorderCells;
    
    public function __construct(
        array $tableBorderCells = [],
    ) {
        // Format Table Border Cell
        $formattedTableBorderCells = [];
        if ($tableBorderCells) {
            foreach ($tableBorderCells as $tableBorderCell) {
                if (!($tableBorderCell instanceof TableBorderCell)) {
                    $formattedTableBorderCells[] = new TableBorderCell(...$tableBorderCell);
                } else {
                    $formattedTableBorderCells[] = $tableBorderCell;
                }
            }
        }
        $this->tableBorderCells = $formattedTableBorderCells;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
