<?php

namespace Chmw\GoogleApi\Services\Sheets\Requests\Spreadsheets;

use Chmw\GoogleApi\Google\Helpers\Helpers;
use Chmw\GoogleApi\Google\Interfaces\Jsonable;

/**
 * @see https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/request#deletesheetrequest
 */
class DeleteSheetRequest implements Jsonable
{
    public int $sheetId;
    
    public function __construct(
        int $sheetId
    ) {
        $this->sheetId = $sheetId;
    }

    public function toJson(): string
    {
        return json_encode(Helpers::getJsonableArray($this));
    }
}
