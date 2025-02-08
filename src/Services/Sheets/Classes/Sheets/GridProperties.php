<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Sheets;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/sheets#gridproperties
 */
class GridProperties implements Jsonable
{
    public int $rowCount;
    public int $columnCount;
    public int $frozenRowCount;
    public int $frozenColumnCount;
    public bool $hideGridlines;
    public bool $rowGroupControlAfter;
    public bool $columnGroupControlAfter;
    
    public function __construct(
        int $rowCount = 1000,
        int $columnCount = 26,
        int $frozenRowCount = 0,
        int $frozenColumnCount = 0,
        bool $hideGridlines = false,
        bool $rowGroupControlAfter = false,
        bool $columnGroupControlAfter = false,
    ) {
        $this->rowCount = $rowCount;
        $this->columnCount = $columnCount;
        $this->frozenRowCount = $frozenRowCount;
        $this->frozenColumnCount = $frozenColumnCount;
        $this->hideGridlines = $hideGridlines;
        $this->rowGroupControlAfter = $rowGroupControlAfter;
        $this->columnGroupControlAfter = $columnGroupControlAfter;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
