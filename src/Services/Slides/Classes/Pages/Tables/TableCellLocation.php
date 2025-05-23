<?php

namespace Anibalealvarezs\GoogleApi\Services\Slides\Classes\Pages\Tables;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/slides/api/reference/rest/v1/presentations.pages/tables#Page.TableCellLocation
 */
class TableCellLocation implements Jsonable
{
    public int $rowIndex;
    public int $columnIndex;
    
    public function __construct(
        int $rowIndex,
        int $columnIndex
    ) {
        $this->rowIndex = $rowIndex;
        $this->columnIndex = $columnIndex;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
