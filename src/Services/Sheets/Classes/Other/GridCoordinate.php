<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/other#gridcoordinate
 */
class GridCoordinate implements Jsonable
{
    public int $sheetId;
    public int $rowIndex;
    public int $columnIndex;
    
    public function __construct(
        int $sheetId,
        int $rowIndex,
        int $columnIndex
    ) {
        $this->sheetId = $sheetId;
        $this->rowIndex = $rowIndex;
        $this->columnIndex = $columnIndex;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
