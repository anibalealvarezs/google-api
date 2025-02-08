<?php

namespace Chmw\GoogleApi\Services\Sheets\Classes\Sheets;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;
use Chmw\GoogleApi\Services\Sheets\Classes\Cells\CellData;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/sheets#rowdata
 * @param CellData[] $values
 * @return RowData
 */
class RowData implements Jsonable
{
    public array $values;
    
    public function __construct(
        array $values,
    ) {
        // Format Rows
        $formattedValues = [];
        if ($values) {
            foreach ($values as $value) {
                if (!($value instanceof CellData)) {
                    $formattedValues[] = new CellData(...$value);
                } else {
                    $formattedValues[] = $value;
                }
            }
        }
        $this->values = $formattedValues;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
