<?php

namespace Anibalealvarezs\GoogleApi\Services\Sheets\Requests\Sheets;

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Anibalealvarezs\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets.sheets/copyTo
 */
class CopySheetToSpreadsheet implements Jsonable
{
    public string $destinationSpreadsheetId;
    
    public function __construct(
        int $destinationSpreadsheetId
    ) {
        $this->destinationSpreadsheetId = $destinationSpreadsheetId;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
