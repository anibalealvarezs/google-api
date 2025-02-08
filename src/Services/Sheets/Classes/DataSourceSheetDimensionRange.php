<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Classes;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;
use Anibalealvarezs\GoogleApi\Services\Sheets\Classes\Other\DataSourceColumnReference;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/request?hl=es-419#datasourcesheetdimensionrange
 * @param DataSourceColumnReference[] $columnReferences
 */
class DataSourceSheetDimensionRange implements Jsonable
{
    public int $sheetId;
    public array $columnReferences;
    
    public function __construct(
        int $sheetId,
        array $columnReferences = null,
    ) {
        $this->sheetId = $sheetId;
        $formattedColumnReferences = [];
        if ($columnReferences) {
            foreach ($columnReferences as $columnReference) {
                if (!($columnReference instanceof DataSourceColumnReference)) {
                    $formattedColumnReferences[] = new DataSourceColumnReference(...$columnReference);
                } else {
                    $formattedColumnReferences[] = $columnReference;
                }
            }
        }
        $this->columnReferences = $formattedColumnReferences;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
