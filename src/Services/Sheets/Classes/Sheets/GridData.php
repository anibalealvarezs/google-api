<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Sheets;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/sheets#griddata
 */
class GridData implements Jsonable
{
    public int $startRow;
    public int $startColumn;
    public array $rowData;
    public array $rowMetadata;
    public array $columnMetadata;
    
    public function __construct(
        int $startRow,
        int $startColumn,
        array $rowData,
        array $rowMetadata,
        array $columnMetadata,
    ) {
        $this->startRow = $startRow;
        $this->startColumn = $startColumn;
        $this->rowData = $rowData;
        $this->rowMetadata = $rowMetadata;
        $this->columnMetadata = $columnMetadata;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
